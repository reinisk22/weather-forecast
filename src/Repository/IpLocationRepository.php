<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\IpLocation;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class IpLocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IpLocation::class);
    }

    public function save(IpLocation $ipLocation): void
    {
        $ipLocation->setCreatedAt(new DateTimeImmutable());

        $this->_em->persist($ipLocation);
        $this->_em->flush();
    }

    public function ofIp(string $ip): ?IpLocation
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.ip = :ip')
            ->setParameter('ip', $ip)
            ->getQuery()
            ->getOneOrNullResult();
    }
}