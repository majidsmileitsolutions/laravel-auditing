<p align="center">
    <a href="http://laravel-auditing.com" target="_blank"><img width="130" src="https://laravel-auditing.com/logo.svg#v2"></a>
</p>

<p align="center">
    <a href="https://scrutinizer-ci.com/g/owen-it/laravel-auditing/build-status/master"><img src="https://scrutinizer-ci.com/g/owen-it/laravel-auditing/badges/build.png?b=master" alt="Build Status"></a>
    <a href="https://scrutinizer-ci.com/g/owen-it/laravel-auditing/build-status/master"><img src="https://scrutinizer-ci.com/g/owen-it/laravel-auditing/badges/quality-score.png?b=master" title="Scrutinizer Code Quality"></a>
    <a href="https://scrutinizer-ci.com/g/owen-it/laravel-auditing/build-status/master"><img src="https://scrutinizer-ci.com/g/owen-it/laravel-auditing/badges/coverage.png?b=master" alt="Code Coverage"></a>
    <a href="https://packagist.org/packages/owen-it/laravel-auditing"><img src="https://poser.pugx.org/owen-it/laravel-auditing/v/stable.svg" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/owen-it/laravel-auditing"><img src="https://poser.pugx.org/owen-it/laravel-auditing/d/total.svg" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/owen-it/laravel-auditing"><img src="https://poser.pugx.org/owen-it/laravel-auditing/license.svg" alt="License"></a>
    <a href="https://discord.gg/csD9ysg"><img src="https://img.shields.io/badge/chat-on%20discord-7289DA.svg" alt="Chat"></a>
</p>

This package will help you understand changes in your Eloquent models, by providing information about possible discrepancies and anomalies that could indicate business concerns or suspect activities. 

Laravel Auditing allows you to keep a history of model changes by simply using a trait. Retrieving the audited data is straightforward, making it possible to display it in various ways.

## Official Documentation

For more information on how to use the package, please refer to our official documentation available on [laravel-auditing.com](https://laravel-auditing.com) or in the [repository](https://github.com/owen-it/laravel-auditing-doc/blob/main/documentation.md) documentation file. Our documentation provides detailed instructions on how to install and use the package, as well as examples and best practices for auditing in Laravel applications.

Thank you for choosing OwenIt\LaravelAuditing!

## Version Information

Version   | Illuminate     | Status                  | PHP Version
:----------|:---------------|:------------------------|:------------
13.x      | 7.x.x - 10.x.x | Active support :rocket: | > = 7.3 \| 8.0
12.x      | 6.x.x - 9.x.x | Active support          | > = 7.3 \| 8.0
11.x      | 5.8.x - 8.x.x | End of life             | > = 7.3
10.x      | 5.8.x - 7.x.x | End of life             | > = 7.2.5
9.x       | 5.8.x - 6.x.x | End of life             | > = 7.1.3
8.x       | 5.2.x - 5.7.x | End of life             | > = 7.0.13
7.x       | 5.2.x - 5.6.x | End of life             | > = 7.0.13
6.x       | 5.2.x - 5.6.x | End of life             | > = 7.0.13
5.x       | 5.2.x - 5.5.x | End of life             | > = 7.0.13
4.x       | 5.2.x - 5.5.x | End of life             | > = 5.5.9
3.x       | 5.2.x - 5.4.x | End of life             | > = 5.5.9
2.x       | 5.1.x - 5.3.x | End of life             | > = 5.5.9

## Contributing
Please see the [contributing](http://laravel-auditing.com/docs/master/contributing) entry for more details.

## Credits
- [Antério Vieira](https://github.com/anteriovieira)
- [Raphael França](https://github.com/raphaelfranca)
- [Quetzy Garcia](https://github.com/quetzyg)
- [All Contributors](https://github.com/owen-it/laravel-auditing/graphs/contributors)

### Special thanks for keeping this project active.
- [Morten D. Hansen](https://github.com/MortenDHansen)
- [erikn69](https://github.com/erikn69)
- [parallels999](https://github.com/parallels999)

## License
The **Laravel Auditing** package is open source software licensed under the [MIT LICENSE](LICENSE.md).

# Smile IT Solutions Forked Edition

### Description
This fork has required `https://github.com/bschmitt/laravel-amqp` and uses rabbit as a message broker, publishes records
from the `audits` table which have `false` value on the `is_queued` field, publishes them into the rabbit mq and sets 
the `is_queue` value to `true`. Then the consumer gets those published messages and after pushing them to the
elasticsearch, it sets the `is_acked` field on the `audits` table to `true`.

### Installation
Add the following lines at the end of the `composer.json` file of your project:
```json
"repositories": [
        {
            "name": "majidsmileitsolutions/laravel-auditing",
            "type": "vcs",
            "url": "git@github.com:majidsmileitsolutions/laravel-auditing.git"
        }
    ]
```

Then add the token on the `auth.json` file of your project based on [this link](https://github.com/settings/tokens/new) and [this link](https://getcomposer.org/doc/articles/authentication-for-private-packages.md#github-oauth):
```json
{
    "github-oauth": {
        "github.com": "token"
    }
}
```

Then install the package using the following command:
```bash
composer require majidsmileitsolutions/laravel-auditing
```

### Publish the audit config file
```bash
php artisan vendor:publish --tag=config_audit
```

**Note:** Please don't forget to add all your desired guards in `config/auth.php` -> `guards` keys on 
the `config/audit.php` -> `guards`; Otherwise, the `user_type` and `user_id` in the `audits` table would be null.
### Publish the audit table migration
```bash
php artisan vendor:publish --tag=migrations_audit
```
**Note:** Don't forget to run `php artisan migrate` so that the `audits` table will be migrated.
### Publish the elasticsearch config file
```bash
php artisan vendor:publish --tag=config_elasticsearch
```

### Publish the Audit model
```bash
php artisan vendor:publish --tag=audit_model
```

### Publish the publisher command
```bash
php artisan vendor:publish --tag=rabbit_publisher_command
```
**Note:** Don't forget to resolve the namespace.

### Publish the consumer command
```bash
php artisan vendor:publish --tag=rabbit_consumer_command
```
**Note:** Don't forget to resolve the namespace.

### Publish the rabbit mq config file
The package does not support group name on publishing the assets, so here's how to publish the `amqp.php` config file.
```bash
php artisan vendor:publish --tag=config_amqp
```

### Setting the environment variables
```dotenv
AUDITING_ENABLED=true

AMQP_HOST=rabbitmq
AMQP_PORT=5672
AMQP_USERNAME=
AMQP_PASSWORD=
AMQP_VHOST=/
AMQP_EXCHANGE=amq.topic
AMQP_EXCHANGE_TYPE=topic

ELASTIC_HOST=http://elasticsearch:9200
```

### Scheduling the commands
Then you need to add the commands to your scheduler, so that they do the work.
You also need a command to [prune](https://laravel.com/docs/10.x/eloquent#pruning-models) the `audits` table (those records which have `is_queue=1` and `is_acked=1`).
