<?php

declare(strict_types=1);

namespace App\Api\Dto;

use App\Exception\LogsCountFiltersException;
use Symfony\Component\HttpFoundation\Request;
use DateTime;

class LogsCountFilters
{
    private const DATE_FORMAT = 'Y-m-d\TH:i:s\Z';

    public const PARAM_NAME_SEARCH_NAMES = 'serviceNames';
    public const PARAM_NAME_STATUS_CODE = 'statusCode';
    public const PARAM_NAME_START_DATE = 'startDate';
    public const PARAM_NAME_END_DATE = 'endDate';

    private ?array $serviceNames = null;
    private ?int $statusCode = null;
    private ?DateTime $startDate = null;
    private ?DateTime $endDate = null;

    public function __construct(Request $request)
    {
        $queryString = ltrim($request->getQueryString() ?? '', '?');

        if (empty($queryString)) {
            return;
        }

        parse_str($queryString, $params);

        $this->setServiceNames($params[self::PARAM_NAME_SEARCH_NAMES] ?? null);
        $this->setStatusCode($params[self::PARAM_NAME_STATUS_CODE] ?? null);
        $this->setDates(
            $params[self::PARAM_NAME_START_DATE] ?? null,
            $params[self::PARAM_NAME_END_DATE] ?? null
        );
    }

    /**
     * @return array|null
     */
    public function getServiceNames(): ?array
    {
        return $this->serviceNames;
    }

    /**
     * @return int|null
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    /**
     * @return DateTime|null
     */
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    /**
     * @return DateTime|null
     */
    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    private function setServiceNames(?string $serviceNames)
    {
        if (null === $serviceNames) {
            return;
        }

        $serviceNames = trim($serviceNames);
        if (0 === strlen($serviceNames)) {
            return;
        }

        $serviceNamesArr = array_filter(array_map('trim', explode(',', $serviceNames)));
        if (0 === count($serviceNamesArr)) {
            return;
        }

        $this->serviceNames = $serviceNamesArr;
    }

    private function setStatusCode(?string $statusCode)
    {
        if (null === $statusCode) {
            return;
        }

        $intStatusCode = filter_var($statusCode, FILTER_VALIDATE_INT);
        if (false === $intStatusCode || $intStatusCode <= 0) {
            throw new LogsCountFiltersException(
                sprintf('Invalid statusCode filter value [%s], positive integer expected', $intStatusCode)
            );
        }

        $this->statusCode = $intStatusCode;
    }

    private function setDates(?string $startDate, ?string $endDate)
    {
        if (null !== $startDate) {
            $readAtStart = DateTime::createFromFormat(self::DATE_FORMAT, $startDate);

            if (false === $readAtStart) {
                throw new LogsCountFiltersException(
                    sprintf('Invalid startDate filter value [%s]', $startDate)
                );
            }

            $this->startDate = $readAtStart;
        }

        if (null !== $endDate) {
            $readAtEnd = DateTime::createFromFormat(self::DATE_FORMAT, $endDate);

            if (false === $readAtEnd) {
                throw new LogsCountFiltersException(
                    sprintf('Invalid endDate filter value [%s]', $endDate)
                );
            }

            $this->endDate = $readAtEnd;
        }

        if (
            null !== $this->startDate
            && null !== $this->endDate
            && $this->startDate->getTimestamp() > $this->endDate->getTimestamp()
        ) {
            throw new LogsCountFiltersException(
                sprintf(
                    'Invalid [startDate, endDate] date range filter values ([%s] is after [%s])',
                    $startDate,
                    $endDate
                )
            );
        }
    }
}
