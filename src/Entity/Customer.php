<?php

namespace App\Entity;

use App\Model\Message;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=32, nullable=false, unique=true)
     *
     * @var string
     */
    public string $customerCode;

    /**
     * @ORM\Column(type="string", length=32)
     *
     * @var string
     */
    public string $notificationType = Message::TYPE_EMAIL;

    /**
     * @return string
     */
    public function getCustomerCode(): ?string
    {
        return $this->customerCode;
    }

    /**
     * @param string $customerCode
     *
     * @return \App\Entity\Customer
     */
    public function setCustomerCode(string $customerCode): self
    {
        $this->customerCode = $customerCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getNotificationType(): string
    {
        return $this->notificationType;
    }

    /**
     * @param string $notificationType
     */
    public function setNotificationType(string $notificationType): void
    {
        $this->notificationType = $notificationType;
    }
}