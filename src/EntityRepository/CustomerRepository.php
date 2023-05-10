<?php

namespace App\EntityRepository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function getAllCustomerCodes(): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('c.customerCode');
        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function getEntityClass(): string
    {
        return Customer::class;
    }
}