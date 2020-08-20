<?php

namespace App\Logging;

use Monolog\Formatter\JsonFormatter;

class GKEFormatter extends JsonFormatter
{
    public function format(array $record): string
    {
        $mappedRecord = $record['extra'];
        $mappedRecord['message'] = $record['message'];
        $mappedRecord['severity'] = $record['level_name'];
        $mappedRecord['thread'] = $record['channel'];
        $mappedRecord['serviceContext'] = $record['context'];
        $mappedRecord['timestamp'] = $record['datetime']->getTimestamp();

        return parent::format($mappedRecord);
    }
}
