<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\LogFileNotAccessibleException;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\LogFileNotFoundException;
use App\Entity\LogsProcessing;
use App\Factory\LogsProcessingFactoryInterface;
use App\Service\Contracts\LogFileImporterInterface;
use App\Service\Contracts\LogLineParserInterface;
use App\Entity\Logs;
use DateTime;

class LogFileImporter implements LogFileImporterInterface
{
    /**
     * max line length @ reading (in bytes)
     */
    private const MAX_LOG_LINE_LENGTH = 5120;

    /**
     * number of lines to be processed before a flush
     */
    private const BATCH_PROCESSED_LINES = 50;

    /**
     * possible return statuses for successful processing
     */
    public const STATUS_ALREADY_FINISHED = 1;
    public const STATUS_FINISHED = 2;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private LogsProcessingFactoryInterface $logsProcessingFactory,
        private LogLineParserInterface $lineParser
    ) {
    }

    /**
     * @throws LogFileNotFoundException|LogFileNotAccessibleException
     */
    public function processFile(string $filePath): int
    {
        if (!file_exists($filePath)) {
            throw new LogFileNotFoundException(
                sprintf('Log file at path "%s" could not be found', $filePath)
            );
        }

        $fp = @fopen($filePath, 'r');
        if (false === $fp) {
            throw new LogFileNotAccessibleException(
                sprintf('Log file at path "%s" could not be accessed, please set appropriate permissions', $filePath)
            );
        }

        $logsProcessingRepository = $this->entityManager->getRepository(LogsProcessing::class);
        /** @var ?LogsProcessing $logProcessingInfo */
        $logProcessingInfo = $logsProcessingRepository->findOneBy(['filePath' => $filePath]);

        if (null !== $logProcessingInfo && null !== $logProcessingInfo->getFinishedAt()) {
            return self::STATUS_ALREADY_FINISHED;
        }

        if (null === $logProcessingInfo) {
            $logProcessingInfo = $this->logsProcessingFactory->create($filePath, new DateTime('now'));
            $this->entityManager->persist($logProcessingInfo);
            $this->entityManager->flush();
            $this->entityManager->refresh($logProcessingInfo);
        }

        $lastProcessedLine = $logProcessingInfo->getLastProcessedLine();

        if (0 < $lastProcessedLine) {
            // skip the number of lines already processed until the first one coming next
            for ($l = 1; $l <= $lastProcessedLine; $l++) {
                fgets($fp, self::MAX_LOG_LINE_LENGTH);
            }
        }

        $processedLines = 0;

        while (($line = fgets($fp, self::MAX_LOG_LINE_LENGTH)) !== false) {
            /** @var Logs $logEntry */
            $logEntry = $this->lineParser->parse($line);

            if (null === $logEntry) {
                // todo: add output logging capability & append "not parsable line" in there.
            }

            if (null !== $logEntry) {
                $this->entityManager->persist($logEntry);
            }

            $processedLines++;

            if ($processedLines%self::BATCH_PROCESSED_LINES === 0) {
                $lastProcessedLine += $processedLines;
                $processedLines = 0;

                $this->updateLogProcessingInfo($logProcessingInfo, $lastProcessedLine);
            }
        }

        if (0 < $processedLines) {
            $this->updateLogProcessingInfo($logProcessingInfo, $lastProcessedLine + $processedLines, true);
        } else {
            $logProcessingInfo->setFinishedAt(new DateTime('now'));
            $this->entityManager->persist($logProcessingInfo);
            $this->entityManager->flush();
        }

        fclose($fp);

        return self::STATUS_FINISHED;
    }

    private function updateLogProcessingInfo(
        LogsProcessing $logProcessingInfo,
        int $lastProcessedLine,
        bool $markFinished = false
    ): void {
        $now = new DateTime('now');

        $logProcessingInfo->setLastProcessedLine($lastProcessedLine);
        $logProcessingInfo->setUpdatedAt($now);

        if ($markFinished) {
            $logProcessingInfo->setFinishedAt($now);
        }

        $this->entityManager->persist($logProcessingInfo);
        $this->entityManager->flush();
    }
}
