<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogsProcessing
 *
 * @ORM\Table(name="logs_processing", uniqueConstraints={@ORM\UniqueConstraint(name="logs_processing_file_path_uq", columns={"file_path"})})
 * @ORM\Entity
 */
class LogsProcessing
{
    /**
     * @var int
     *
     * @ORM\Column(name="log_processing_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="logs_processing_log_processing_id_seq", allocationSize=1, initialValue=1)
     */
    private $logProcessingId;

    /**
     * @var string
     *
     * @ORM\Column(name="file_path", type="string", length=255, nullable=false)
     */
    private $filePath;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="started_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $startedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="finished_at", type="datetime", nullable=true)
     */
    private $finishedAt;

    /**
     * @var int
     *
     * @ORM\Column(name="last_processed_line", type="integer", nullable=false)
     */
    private $lastProcessedLine = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $updatedAt;

    /**
     * @return int
     */
    public function getLogProcessingId(): int
    {
        return $this->logProcessingId;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath(string $filePath): void
    {
        $this->filePath = $filePath;
    }

    /**
     * @return \DateTime
     */
    public function getStartedAt(): \DateTime|string
    {
        return $this->startedAt;
    }

    /**
     * @param \DateTime $startedAt
     */
    public function setStartedAt(\DateTime $startedAt): void
    {
        $this->startedAt = $startedAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getFinishedAt(): ?\DateTime
    {
        return $this->finishedAt;
    }

    /**
     * @param \DateTime|null $finishedAt
     */
    public function setFinishedAt(?\DateTime $finishedAt): void
    {
        $this->finishedAt = $finishedAt;
    }

    /**
     * @param int $logProcessingId
     *
     * @return LogsProcessing
     */
    public function setLogProcessingId(int $logProcessingId): LogsProcessing
    {
        $this->logProcessingId = $logProcessingId;
        return $this;
    }

    /**
     * @return int
     */
    public function getLastProcessedLine(): int|string
    {
        return $this->lastProcessedLine;
    }

    /**
     * @param int $lastProcessedLine
     */
    public function setLastProcessedLine(int|string $lastProcessedLine): void
    {
        $this->lastProcessedLine = $lastProcessedLine;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime|string
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime|string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
