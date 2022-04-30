<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use ApiPlatform\Core\Annotation\{ApiProperty, ApiResource, ApiFilter};
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Action\NotFoundAction;
use App\Api\Dto\LogsCountOutput;
use App\Api\Controller\LogsCountController;

/**
 * Logs
 *
 * @ORM\Table(
 *     name="logs",
 *     indexes={
 *          @Index(name="service_name_idx", columns={"service_name"}),
 *          @Index(name="status_code_idx", columns={"status_code"}),
 *          @Index(name="recorded_at_idx", columns={"recorded_at"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\LogsRepository")
 *
 * @ApiResource(
 *     shortName="Logs",
 *     formats={"json"},
 *     attributes={"pagination_enabled"=false},
 *     itemOperations={},
 *     collectionOperations={
 *          "count"={
 *              "method"="GET",
 *              "description"="Count the log entries matching filters set in the query string",
 *              "path"="/count",
 *              "output"={"class"=LogsCountOutput::class},
 *              "controller"=LogsCountController::class,
 *              "openapi_context"={
 *                  "summary"="searches logs and provides aggregated count of matches",
 *                  "description"="Count all matching items in the logs",
 *                  "parameters"={
 *                      {
 *                          "in": "query",
 *                          "name": "serviceNames",
 *                          "required": false,
 *                          "schema": {
 *                              "type"="array"
 *                          },
 *                          "description": "array of service names"
 *                      },
 *                      {
 *                          "in": "query",
 *                          "name": "startDate",
 *                          "required": false,
 *                          "schema": {
 *                              "type"="string",
 *                              "format"="datetime"
 *                          },
 *                          "description": "start date",
 *                          "example": "2022-04-25T13:30:00Z"
 *                      },
 *                      {
 *                          "in": "query",
 *                          "name": "endDate",
 *                          "required": false,
 *                          "schema": {
 *                              "type"="string",
 *                              "format"="datetime"
 *                          },
 *                          "description": "end date",
 *                          "example": "2022-04-25T18:30:00Z"
 *                      },
 *                      {
 *                          "in": "query",
 *                          "name": "statusCode",
 *                          "required": false,
 *                          "schema": {
 *                              "type"="integer"
 *                          },
 *                          "description": "filter on request status code"
 *                      }
 *                  },
 *                  "responses"={
 *                      "200"={
 *                          "description"="count of matching results",
 *                          "content"={
 *                              "application/json"={
 *                                  "schema"={
 *                                      "$ref"="#/components/schemas/Logs.LogsCountOutput"
 *                                  }
 *                              }
 *                          }
 *                      },
 *                      "400"={
 *                          "description"="bad input parameter"
 *                      }
 *                  }
 *              }
 *          }
 *     }
 * )
 */
class Logs
{
    /**
     * @var int
     *
     * @ORM\Column(name="log_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="logs_log_id_seq", allocationSize=1, initialValue=1)
     */
    private $logId;

    /**
     * @var string
     *
     * @ORM\Column(name="service_name", type="string", length=50, nullable=false)
     */
    private $serviceName;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="recorded_at", type="datetime", nullable=false)
     */
    private $recordedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="method", type="string", length=10, nullable=false)
     */
    private $method;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=100, nullable=false)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="protocol", type="string", length=15, nullable=false)
     */
    private $protocol;

    /**
     * @var int
     *
     * @ORM\Column(name="status_code", type="integer", nullable=false)
     */
    private $statusCode;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @return int
     */
    public function getLogId(): int
    {
        return $this->logId;
    }

    /**
     * @param int $logId
     */
    public function setLogId(int $logId): void
    {
        $this->logId = $logId;
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    /**
     * @param string $serviceName
     */
    public function setServiceName(string $serviceName): void
    {
        $this->serviceName = $serviceName;
    }

    /**
     * @return DateTime
     */
    public function getRecordedAt(): DateTime
    {
        return $this->recordedAt;
    }

    /**
     * @param DateTime $recordedAt
     */
    public function setRecordedAt(DateTime $recordedAt): void
    {
        $this->recordedAt = $recordedAt;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    /**
     * @param string $protocol
     */
    public function setProtocol(string $protocol): void
    {
        $this->protocol = $protocol;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
