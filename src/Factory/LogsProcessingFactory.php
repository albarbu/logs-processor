<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\LogsProcessing;
use DateTime;

class LogsProcessingFactory implements LogsProcessingFactoryInterface
{
    public function create(
        string $filePath,
        DateTime $startedAt,
        ?DateTime $finishedAt = null,
        int $lastProcessedLine = 0,
        ?DateTime $updatedAt = null,
        ?int $id = null,
    ): LogsProcessing {
        $logsProcessing = new LogsProcessing();

        $logsProcessing->setFilePath($filePath);
        $logsProcessing->setStartedAt($startedAt);
        $logsProcessing->setLastProcessedLine($lastProcessedLine);

        if (null !== $finishedAt) {
            $logsProcessing->setFinishedAt($finishedAt);
        }

        $updatedAt = $updatedAt ?? $startedAt;
        $logsProcessing->setUpdatedAt($updatedAt);

        if (null !== $id) {
            $logsProcessing->setLogProcessingId($id);
        }

        return $logsProcessing;
    }
}
