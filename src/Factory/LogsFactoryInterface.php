<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Logs;
use DateTime;

interface LogsFactoryInterface
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
    ): Logs;
}
