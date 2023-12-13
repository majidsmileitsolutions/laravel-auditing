<?php

namespace OwenIt\Auditing\Console\Rabbit;

use Bschmitt\Amqp\Facades\Amqp;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Console\BaseCommand;
use OwenIt\Auditing\Drivers\ElasticsearchClient;
use OwenIt\Auditing\Models\Audit;
use RuntimeException;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class RabbitAuditConsumerCommand extends BaseCommand
{
    private Audit $audit;

    private string $queue;

    private string $elasticBaseUrl;

    private string $elasticIndex;

    public function __construct(Audit $audit)
    {
        parent::__construct();
        $this->audit = $audit;
        $this->elasticBaseUrl = config('elasticsearch.base_url', 'your_elastic_base_url');
        $this->queue = config('amqp.audit_queue', 'audit_queue');
        $this->elasticIndex = config('elasticsearch.audits_index', 'your_app_name_your_env_audits');
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
                $now = now();
                $auditArray['elastic_created_at'] = $now->format('Y-m-d H:i:s');
                $auditArray['created_at_timestamp'] = Carbon::parse($auditArray['created_at'])->timestamp;
                $auditArray['updated_at_timestamp'] = Carbon::parse($auditArray['updated_at'])->timestamp;
                $auditArray['elastic_created_at_timestamp'] = $now->timestamp;
                $elasticResponse = ElasticsearchClient::build()
                                  ->post($this->elasticBaseUrl."/$this->elasticIndex/_doc", $auditArray);
                if ($elasticResponse->status() !== Response::HTTP_CREATED) {
                    Log::error('failed_to_index_document', [
                         'status_code' => $elasticResponse->status(),
                         'response' => $elasticResponse->json(),
                    ]);
                    throw new RuntimeException('failed_to_index_document');
                }
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
