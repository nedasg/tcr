<?php

namespace App\Service;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;

class MessageManager {

    public EntityManagerInterface $entityManager;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \App\Entity\Message $message
     *
     * @return void
     */
    public function save(Message $message)
    {
        $this->entityManager->persist($message);
        $this->entityManager->flush();
    }
}