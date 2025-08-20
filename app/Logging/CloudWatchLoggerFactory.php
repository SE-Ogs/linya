<?php

namespace App\Logging;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Maxbanton\Cwh\Handler\CloudWatch;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger;

class CloudWatchLoggerFactory
{
    public function __invoke(array $config)
    {
        // Prefer instance profile (IAM role). If keys exist in env, SDK will use them.
        $client = new CloudWatchLogsClient([
            'region'  => $config['region'] ?? env('AWS_DEFAULT_REGION', 'ap-southeast-2'),
            'version' => 'latest',
            // If you *must* force static creds, uncomment below:
            // 'credentials' => [
            //     'key'    => env('AWS_ACCESS_KEY_ID'),
            //     'secret' => env('AWS_SECRET_ACCESS_KEY'),
            //     'token'  => env('AWS_SESSION_TOKEN'),
            // ],
        ]);

        $groupName  = $config['group']      ?? env('CLOUDWATCH_LOG_GROUP', 'laravel-app');
        $streamName = $config['stream']     ?? env('CLOUDWATCH_LOG_STREAM', php_uname('n')); // hostname/container id
        $retention  = (int)($config['retention']  ?? env('CLOUDWATCH_LOG_RETENTION_DAYS', 14));
        $batchSize  = (int)($config['batch_size'] ?? 10000);
        $tags       = $config['tags']       ?? ['app' => env('APP_NAME', 'laravel')];

        $handler = new CloudWatch(
            $client,
            $groupName,
            $streamName,
            $retention,   // days (0 keeps forever)
            $batchSize,   // max log events per batch
            $tags
        );

        // Pretty, multiline context; keep newlines
        $handler->setFormatter(new LineFormatter(null, null, true, true));

        $logger = new Logger($config['name'] ?? 'cloudwatch');
        $logger->pushHandler($handler);

        return $logger;
    }
}

