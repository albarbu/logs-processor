<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Filesystem\Exception\IOException;

class LogFileNotAccessibleException extends IOException
{
}
