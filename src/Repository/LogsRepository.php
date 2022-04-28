<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use App\Api\Dto\LogsCountFilters;
use App\Entity\Logs;

class LogsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Logs::class);
    }

    public function getCountForFilters(LogsCountFilters $filters): int
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('l');

        $query = $qb->select('count(l.logId)')->where('1 = 1');

        $serviceNames = $filters->getServiceNames();
        if (!empty($serviceNames)) {
            $query->andWhere($qb->expr()->in('l.serviceName', ':serviceNames'))
                ->setParameter('serviceNames', $serviceNames);
        }

        $startDate = $filters->getStartDate();
        if (null !== $startDate) {
            $query->andWhere($qb->expr()->gte('l.recordedAt', ':startDate'))
                ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'));
        }

        $endDate = $filters->getEndDate();
        if (null !== $endDate) {
            $query->andWhere($qb->expr()->lte('l.recordedAt', ':endDate'))
                ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'));
        }

        $statusCode = $filters->getStatusCode();
        if (null !== $statusCode) {
            $query->andWhere($qb->expr()->eq('l.statusCode', ':statusCode'))
                ->setParameter('statusCode', $statusCode);
        }

        return $query->getQuery()->getSingleScalarResult();
    }
}
