<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="customer")
     */
    private $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

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

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setCustomer($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getCustomer() === $this) {
                $message->setCustomer(null);
            }
        }

        return $this;
    }
}