<?php

declare(strict_types=1);

namespace App\Service\Contracts;

interface LogFileImporterInterface
{
    public function processFile(string $filePath): int;
}
