<?php

declare(strict_types=1);

namespace App\Tests\unit\Service;

use App\Entity\Logs;
use App\Factory\LogsFactory;
use App\Factory\LogsFactoryInterface;
use App\Service\LogLineParser;
use PHPUnit\Framework\TestCase;
use DateTime;

class LogLineParserTest extends TestCase
{
    private LogsFactoryInterface $logsFactory;
    private LogLineParser $testedObject;

    public function setUp(): void
    {
        $this->logsFactory = new LogsFactory();

        $this->testedObject = new LogLineParser($this->logsFactory);
    }

    public function testParse(): void
    {
        $serviceName = 'TEST-SERVICE';

        $recordedAtDatetime = new DateTime('2022-04-27 12:00:00');
        $recordedAt = $recordedAtDatetime->format('d/M/Y:H:i:s O');

        $method = 'POST';
        $path = '/test';
        $protocol = 'HTTP/1.1';
        $requestDetails = implode(' ', [$method, $path, $protocol]);

        $statusCode = '201';

        $line = sprintf('%s - - [%s] "%s" %s', $serviceName, $recordedAt, $requestDetails, $statusCode);
        $parsedLine = $this->testedObject->parse($line);

        $this->assertInstanceOf(Logs::class, $parsedLine);

        $this->assertEquals($serviceName, $parsedLine->getServiceName());
        $this->assertEquals($recordedAtDatetime, $parsedLine->getRecordedAt());
        $this->assertEquals($method, $parsedLine->getMethod());
        $this->assertEquals($path, $parsedLine->getPath());
        $this->assertEquals($protocol, $parsedLine->getProtocol());
        $this->assertEquals((int) $statusCode, $parsedLine->getStatusCode());
    }

    public function testParseWhenLineCouldNotBeParsed(): void
    {
        $line = 'INVALID-LINE that cannot be parsed';
        $result = $this->testedObject->parse($line);

        $this->assertNull($result);
    }
}
