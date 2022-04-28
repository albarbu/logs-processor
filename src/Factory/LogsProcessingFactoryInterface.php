<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\LogsProcessing;
use DateTime;

interface LogsProcessingFactoryInterface
{
    public function create(
        string $filePath,
        DateTime $startedAt,
        ?DateTime $finishedAt = null,
        int $lastProcessedLine = 0,
        ?DateTime $updatedAt = null,
        ?int $id = null,
    ): LogsProcessing;
}
