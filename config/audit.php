<?php

use Illuminate\Validation\Rule;
use OwenIt\Auditing\Constants\EventTypes;

return [

     'enabled' => env('AUDITING_ENABLED', true),

     /*
     |--------------------------------------------------------------------------
     | Audit Implementation
     |--------------------------------------------------------------------------
     |
     | Define which Audit model implementation should be used.
     |
     */

     'implementation' => OwenIt\Auditing\Models\Audit::class,

     /*
     |--------------------------------------------------------------------------
     | User Morph prefix & Guards
     |--------------------------------------------------------------------------
     |
     | Define the morph prefix and authentication guards for the User resolver.
     |
     */

     'user'      => [
          'morph_prefix' => 'user',
          'guards'       => [
               'web',
               'api',
               //any other guards can be added here: like 'admin-api'
          ],
          'resolver'     => OwenIt\Auditing\Resolvers\UserResolver::class,
     ],

     /*
     |--------------------------------------------------------------------------
     | Audit Resolvers
     |--------------------------------------------------------------------------
     |
     | Define the IP Address, User Agent and URL resolver implementations.
     |
     */
     'resolvers' => [
          'ip_address' => OwenIt\Auditing\Resolvers\IpAddressResolver::class,
          'user_agent' => OwenIt\Auditing\Resolvers\UserAgentResolver::class,
          'url'        => OwenIt\Auditing\Resolvers\UrlResolver::class,
     ],

     /*
     |--------------------------------------------------------------------------
     | Audit Events
     |--------------------------------------------------------------------------
     |
     | The Eloquent events that trigger an Audit.
     |
     */

     'events' => [
          EventTypes::CREATED,
          EventTypes::UPDATED,
          EventTypes::DELETED,
          EventTypes::RESTORED,
     ],

     /*
     |--------------------------------------------------------------------------
     | Strict Mode
     |--------------------------------------------------------------------------
     |
     | Enable the strict mode when auditing?
     |
     */

     'strict' => false,

     /*
     |--------------------------------------------------------------------------
     | Global exclude
     |--------------------------------------------------------------------------
     |
     | Have something you always want to exclude by default? - add it here.
     | Note that this is overwritten (not merged) with local exclude
     |
     */

     'exclude' => [],

     /*
     |--------------------------------------------------------------------------
     | Empty Values
     |--------------------------------------------------------------------------
     |
     | Should Audit records be stored when the recorded old_values & new_values
     | are both empty?
     |
     | Some events may be empty on purpose. Use allowed_empty_values to exclude
     | those from the empty values check. For example when auditing
     | model retrieved events which will never have new and old values.
     |
     |
     */

     'empty_values'         => true,
     'allowed_empty_values' => [
          'retrieved',
     ],

     /*
     |--------------------------------------------------------------------------
     | Audit Timestamps
     |--------------------------------------------------------------------------
     |
     | Should the created_at, updated_at and deleted_at timestamps be audited?
     |
     */

     'timestamps' => false,

     /*
     |--------------------------------------------------------------------------
     | Audit Threshold
     |--------------------------------------------------------------------------
     |
     | Specify a threshold for the amount of Audit records a model can have.
     | Zero means no limit.
     |
     */

     'threshold' => 0,

     /*
     |--------------------------------------------------------------------------
     | Audit Driver
     |--------------------------------------------------------------------------
     |
     | The default audit driver used to keep track of changes.
     |
     */

     'driver' => 'database',

     /*
     |--------------------------------------------------------------------------
     | Audit Driver Configurations
     |--------------------------------------------------------------------------
     |
     | Available audit drivers and respective configurations.
     |
     */

     'drivers' => [
          'database' => [
               'table'      => 'audits',
               'connection' => env('DB_CONNECTION'),
          ],
     ],

     /*
     |--------------------------------------------------------------------------
     | Audit Console
     |--------------------------------------------------------------------------
     |
     | Whether console events should be audited (eg. php artisan db:seed).
     |
     */

     'console' => false,

     /*
     |--------------------------------------------------------------------------
     | Middleware
     |--------------------------------------------------------------------------
     |
     | The middleware you want to be assigned to the audits search route.
     |
     */

     'search_middleware_name' => ['auth:admin-api'],

     /*
     |--------------------------------------------------------------------------
     | Permission
     |--------------------------------------------------------------------------
     |
     | There might be a permission name for searching
     |
     */

     'search_permission_name' => '', // ex. 'SEARCH_AUDITS'

     /*
     |--------------------------------------------------------------------------
     | Search Request Validation Considerations
     |--------------------------------------------------------------------------
     |
     | Customize your search request validations based on your project logic
     |
     */

     'search_request_validation_rules' => [
          'model'     => ['nullable', 'string'/*, Rule::in([])*/],
          'action'    => [
               'nullable',
               'array',
               Rule::in([
                         EventTypes::CREATED,
                         EventTypes::UPDATED,
                         EventTypes::DELETED,
                         EventTypes::RESTORED,
                    ]),
          ],
          'from_date' => ['nullable', 'date', 'date_format:Y-m-d'],
          'to_date'   => ['nullable', 'date', 'date_format:Y-m-d'],
          'field'     => ['nullable', 'string'],
          'keyword'   => ['nullable', 'string'],
     ],

     'search_request_validation_authorize' => true,
];
