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
    /**
     * {@inheritDoc}
     */
    public function getEntityClass(): string
    {
        return Customer::class;
    }
}