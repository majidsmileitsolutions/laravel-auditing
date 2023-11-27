<?php

namespace OwenIt\Auditing;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use OwenIt\Auditing\Console\AuditDriverCommand;
use OwenIt\Auditing\Console\AuditResolverCommand;
use OwenIt\Auditing\Console\InstallCommand;
use OwenIt\Auditing\Console\Rabbit\RabbitAuditConsumerCommand;
use OwenIt\Auditing\Console\Rabbit\RabbitAuditPublisherCommand;
use OwenIt\Auditing\Contracts\Auditor;

class AuditingServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishing();
        $this->mergeConfigFrom(__DIR__.'/../config/audit.php', 'audit');
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            AuditDriverCommand::class,
            AuditResolverCommand::class,
            InstallCommand::class,
            RabbitAuditPublisherCommand::class,
            RabbitAuditConsumerCommand::class,
        ]);

        $this->app->singleton(Auditor::class, function ($app) {
            return new \OwenIt\Auditing\Auditor($app);
        });

        $this->app->register(AuditingEventServiceProvider::class);
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            // Lumen lacks a config_path() helper, so we use base_path()
            $this->publishes([
                __DIR__.'/../config/audit.php' => base_path('config/audit.php'),
            ], 'config_audit');

            $this->publishes([
                __DIR__.'/../config/elasticsearch.php' => base_path('config/elasticsearch.php'),
            ], 'config_elasticsearch');

            $this->publishes([
                __DIR__.'/../config/amqp.php' => base_path('config/amqp.php'),
            ], 'config_amqp');

            $this->publishes([
                __DIR__.'/../src/Console/Rabbit/RabbitAuditPublisherCommand.php' => base_path('app/Console/Commands/Rabbit/RabbitAuditPublisherCommand.php'),
            ], 'rabbit_publisher_command');

            $this->publishes([
                __DIR__.'/../src/Console/Rabbit/RabbitAuditConsumerCommand.php' => base_path('app/Console/Commands/Rabbit/RabbitAuditConsumerCommand.php'),
            ], 'rabbit_consumer_command');

            $this->publishes([
                __DIR__.'/../src/Models/Audit.php' => base_path('app/Models/Audit.php')
            ], 'audit_model');

            $this->publishes([
                __DIR__.'/../database/migrations/mappings/audits_elasticsearch_mapping.json' => base_path('migrations/mappings/audits_elasticsearch_mapping.json')
            ], 'audit_elasticsearch_mapping');

            if (! class_exists('CreateAuditsTable')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/audits_elasticsearch_index.stub' => database_path(
                        sprintf('migrations/%s_create_audits_elasticsearch_index.php', date('Y_m_d_His'))
                    ),
                ], 'migrations_audit_elastic_index');
            }

            if (! class_exists('CreateAuditsTable')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/audits.stub' => database_path(
                        sprintf('migrations/%s_create_audits_table.php', date('Y_m_d_His'))
                    ),
                ], 'migrations_audit');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function provides()
    {
        return [
            Auditor::class,
        ];
    }
}
