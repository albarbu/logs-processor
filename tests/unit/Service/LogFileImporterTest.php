<?php

declare(strict_types=1);

namespace App\Tests\unit\Service;

use App\Exception\LogFileNotAccessibleException;
use App\Factory\LogsFactory;
use App\Factory\LogsProcessingFactory;
use App\Service\LogLineParser;
use App\Service\LogFileImporter;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\LogFileNotFoundException;
use App\Entity\LogsProcessing;
use App\Repository\LogsRepository;
use App\Factory\LogsProcessingFactoryInterface;
use App\Service\Contracts\LogLineParserInterface;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;

class LogFileImporterTest extends TestCase
{
    private LogFileImporter $testedObject;
    private EntityManagerInterface $entityManager;
    private LogsProcessingFactoryInterface $logsProcessingFactory;
    private LogLineParserInterface $lineParser;

    public function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->logsProcessingFactory = $this->createMock(LogsProcessingFactoryInterface::class);
        $this->lineParser = $this->createMock(LogLineParserInterface::class);

        $this->testedObject = new LogFileImporter(
            $this->entityManager,
            $this->logsProcessingFactory,
            $this->lineParser
        );
    }

    public function testProcessFileWithFirstTimeExecution(): void
    {
        $filePath = realpath(__DIR__ . '/../../_data/logs_sample.txt');

        $logsProcessingRepository = $this->createMock(ObjectRepository::class);
        $logsProcessingRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['filePath' => $filePath])
            ->willReturn(null);

        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->with(LogsProcessing::class)
            ->willReturn($logsProcessingRepository);

        $logProcessingInfo = $this->generateLogsProcessingEntry($filePath);
        $this->logsProcessingFactory->expects($this->once())
            ->method('create')
            ->with($filePath, $this->anything())
            ->willReturn($logProcessingInfo);

        $result = $this->testedObject->processFile($filePath);

        $this->assertEquals(LogFileImporter::STATUS_FINISHED, $result);
        $this->assertEquals(2, $logProcessingInfo->getLastProcessedLine());
        $this->assertNotNull($logProcessingInfo->getFinishedAt());
    }

    public function testProcessFileWithResumeExecution(): void
    {
        $filePath = realpath(__DIR__ . '/../../_data/logs_sample.txt');

        $logProcessingInfo = $this->generateLogsProcessingEntry($filePath, 1);

        $logsProcessingRepository = $this->createMock(ObjectRepository::class);
        $logsProcessingRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['filePath' => $filePath])
            ->willReturn($logProcessingInfo);

        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->with(LogsProcessing::class)
            ->willReturn($logsProcessingRepository);

        $this->logsProcessingFactory->expects($this->never())->method('create');

        $result = $this->testedObject->processFile($filePath);

        $this->assertEquals(LogFileImporter::STATUS_FINISHED, $result);
        $this->assertEquals(2, $logProcessingInfo->getLastProcessedLine());
        $this->assertNotNull($logProcessingInfo->getFinishedAt());
    }

    public function testProcessFileWithAlreadyFinishedExecution(): void
    {
        $filePath = realpath(__DIR__ . '/../../_data/logs_sample.txt');

        $finishedAt = new DateTime('-2 minute');
        $logProcessingInfo = $this->generateLogsProcessingEntry($filePath, 2, $finishedAt);

        $logsProcessingRepository = $this->createMock(ObjectRepository::class);
        $logsProcessingRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['filePath' => $filePath])
            ->willReturn($logProcessingInfo);

        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->with(LogsProcessing::class)
            ->willReturn($logsProcessingRepository);

        $this->logsProcessingFactory->expects($this->never())->method('create');

        $result = $this->testedObject->processFile($filePath);

        $this->assertEquals(LogFileImporter::STATUS_ALREADY_FINISHED, $result);
        $this->assertEquals(2, $logProcessingInfo->getLastProcessedLine());
        $this->assertEquals($finishedAt, $logProcessingInfo->getFinishedAt());
    }

    public function testProcessFileWithNotFoundFileException(): void
    {
        $filePath = realpath(__DIR__ . '/../../_data/') . '/404.txt';
        $this->expectException(LogFileNotFoundException::class);
        $this->expectExceptionMessageMatches('/Log file at path ".+" could not be found/');

        $this->testedObject->processFile($filePath);
    }

    /**
     * Note: this test case will run properly only when executed as a non-root user !
     */
    public function testProcessFileWithNotAccessibleFileException(): void
    {
        $filePath = '/etc/shadow';
        $this->expectException(LogFileNotAccessibleException::class);
        $this->expectExceptionMessageMatches('/Log file at path ".+" could not be accessed/');

        $this->testedObject->processFile($filePath);
    }

    private function generateLogsProcessingEntry(
        string $filePath,
        int $lastProcessedLine = 0,
        ?DateTime $finishedAt = null,
        int $id = 1
    ): LogsProcessing {
        $logsProcessing = new LogsProcessing();

        $logsProcessing->setFilePath($filePath);
        $logsProcessing->setLastProcessedLine($lastProcessedLine);
        $logsProcessing->setStartedAt(new DateTime('now'));
        $logsProcessing->setFinishedAt($finishedAt);
        $logsProcessing->setLogProcessingId($id);

        return $logsProcessing;
    }
}
