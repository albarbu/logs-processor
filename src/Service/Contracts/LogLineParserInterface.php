<?php

declare(strict_types=1);

namespace App\Service\Contracts;

use App\Entity\Logs;

interface LogLineParserInterface
{
    public function parse(string $line): ?Logs;
}
