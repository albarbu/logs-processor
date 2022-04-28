<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Logs;
use DateTime;

class LogsFactory implements LogsFactoryInterface
{
    public function create(
        string $serviceName,
        DateTime $recordedAt,
        string $method,
        string $path,
        string $protocol,
        int $statusCode,
        ?DateTime $createdAt = null,
        ?int $id = null,
    ): Logs {
        $log = new Logs();

        $log->setServiceName($serviceName);
        $log->setRecordedAt($recordedAt);
        $log->setMethod($method);
        $log->setPath($path);
        $log->setProtocol($protocol);
        $log->setStatusCode($statusCode);
        $log->setCreatedAt($createdAt ?? new DateTime('now'));

        if (null !== $id) {
            $log->setLogId($id);
        }

        return $log;
    }
}
