<?php

namespace OwenIt\Auditing\Console\Rabbit;

use Bschmitt\Amqp\Facades\Amqp;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use OwenIt\Auditing\Console\BaseCommand;
use OwenIt\Auditing\Models\Audit;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class RabbitAuditConsumerCommand extends BaseCommand
{
    private Audit $audit;

    private string $queue;

    private string $elasticIndex;

    public function __construct(Audit $audit)
    {
        parent::__construct();
        $this->audit = $audit;
        $this->queue = config('amqp.audit_queue');
        $this->elasticIndex = config('elasticsearch.audits_index');
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:rabbit-consumer-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume audits rabbit messages.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            Amqp::consume($this->queue, function ($message, $resolver) {
                $auditArray = json_decode($message->body, true);
                DB::beginTransaction();
                $auditArray['is_queued'] = true;
                $auditArray['is_acked'] = true;
                Http::post(config('elasticsearch.base_url')."/$this->elasticIndex/_doc", $auditArray);
                $resolver->acknowledge($message);
                $this->audit->query()->where('id', $auditArray['id'])->update([
                    'is_acked' => true,
                ]);
                DB::commit();
                $resolver->stopWhenProcessed();
            });

            return SymfonyCommand::SUCCESS;
        } catch (Exception $exception) {
            $this->error($exception->getMessage());

            return SymfonyCommand::FAILURE;
        }
    }
}
