<?php

namespace OwenIt\Auditing\Console\Rabbit;

use Bschmitt\Amqp\Facades\Amqp;
use Exception;
use OwenIt\Auditing\Console\BaseCommand;
use OwenIt\Auditing\Constants\RabbitQueues;
use OwenIt\Auditing\Models\Audit;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class RabbitAuditPublisherCommand extends BaseCommand
{
    private string $queue;

    private Audit $audit;

    public function __construct(Audit $audit)
    {
        parent::__construct();
        $this->audit = $audit;
        $this->queue = config('amqp.audit_queue', 'audit_queue');
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:rabbit-publisher-command {--take=500}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish audits to rabbit messages.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $take = $this->option('take');
            $audits = $this->audit->query()->where('is_queued', 0)->take($take)->get();
            $auditsCount = $audits->count();

            /**
             * @var $audit Audit
             */
            foreach ($audits as $audit) {
                $message = json_encode($audit->toArray());
                Amqp::publish(config('amqp.properties.production.vhost'), $message, [
                    'queue' => $this->queue,
                ]);
                $audit->update(['is_queued' => true]);
            }

            $this->info("Successfully published $auditsCount messages to the rabbit.");

            return SymfonyCommand::SUCCESS;
        } catch (Exception $exception) {
            $this->error($exception->getMessage());

            return SymfonyCommand::FAILURE;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'take' => ['nullable', 'integer', 'min:1', 'max:1000'],
        ];
    }
}
