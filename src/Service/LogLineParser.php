<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Logs;
use App\Factory\LogsFactoryInterface;
use App\Service\Contracts\LogLineParserInterface;
use DateTime;

class LogLineParser implements LogLineParserInterface
{
    private const LINE_REGEXP_PATTERN = '/([A-Z\-]*)\s\-\s\-\s\[(.*)\]\s\"(.*)\"\s(\d*)/';
    private const RECORDED_AT_DATE_FORMAT = 'd/M/Y:H:i:s O';

    public function __construct(
        private LogsFactoryInterface $logsFactory
    ) {
    }

    public function parse(string $line): ?Logs
    {
        preg_match(self::LINE_REGEXP_PATTERN, $line, $matches);

        if (0 === count($matches)) {
            return null;
        }

        $serviceName = $matches[1];
        $recordedAt = DateTime::createFromFormat(self::RECORDED_AT_DATE_FORMAT, $matches[2]);

        $requestDetails = $matches[3];
        list ($method, $path, $protocol) = explode(' ', $requestDetails);

        $statusCode = (int) $matches[4];

        return $this->logsFactory->create(
            $serviceName,
            $recordedAt,
            $method,
            $path,
            $protocol,
            $statusCode
        );
    }
}
