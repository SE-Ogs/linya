<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Processor\PsrLogMessageProcessor;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that is utilized to write
    | messages to your logs. The value provided here should match one of
    | the channels present in the list of "channels" configured below.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the log channel that should be used to log warnings
    | regarding deprecated PHP and library features. This allows you to get
    | your application ready for upcoming major versions of dependencies.
    |
    */

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace' => env('LOG_DEPRECATIONS_TRACE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Laravel
    | utilizes the Monolog PHP logging library, which includes a variety
    | of powerful log handlers and formatters that you're free to use.
    |
    | Available drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog", "custom", "stack"
    |
    */


    'channels' => [

        'stack' => [
            'driver' => 'stack',
            'channels' => ['single', 'cloudwatch'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        // 'cloudwatch' => [
        //     'driver' => 'custom',
        //     'via'    => \App\Logging\CloudWatchLoggerFactory::class,
        //     'level'  => env('LOG_LEVEL', 'info'),
        //     // Optional overrides (you can omit; factory reads env defaults)
        //     'region'    => env('AWS_DEFAULT_REGION', 'ap-southeast-2'),
        //     'group'     => env('CLOUDWATCH_LOG_GROUP', 'laravel-app'),
        //     'stream'    => env('CLOUDWATCH_LOG_STREAM', php_uname('n')),
        //     'retention' => env('CLOUDWATCH_LOG_RETENTION_DAYS', 14),
        //     'batch_size' => 10000,
        //     'tags'      => ['env' => env('APP_ENV', 'production')],
        // ],
    ],

];
