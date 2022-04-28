<?php

declare(strict_types=1);

namespace App\Api\Dto;

class LogsCountOutput
{
    private int $counter = 0;

    public function getCounter(): int
    {
        return $this->counter;
    }

    public function setCounter(int $counter): self
    {
        $this->counter = $counter;

        return $this;
    }
}
