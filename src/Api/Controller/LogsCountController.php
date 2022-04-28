<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\Dto\LogsCountFilters;
use App\Repository\LogsRepository;
use App\Exception\LogsCountFiltersException;
use App\Api\Dto\LogsCountOutput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

class LogsCountController extends AbstractController
{
    public function __construct(
        private LogsRepository $logsRepository
    ) {
    }

    /**
     * @throws BadRequestException
     */
    public function __invoke(Request $request): LogsCountOutput
    {
        try {
            $filters = new LogsCountFilters($request);
        } catch  (LogsCountFiltersException $e) {
            throw new BadRequestException($e->getMessage());
        }

        $count = $this->logsRepository->getCountForFilters($filters);

        return (new LogsCountOutput())->setCounter($count);
    }
}
