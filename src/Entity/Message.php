<?php

namespace App\Entity;

use App\EntityRepository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    public const TYPE_SMS = 'sms';
    public const TYPE_EMAIL = 'email';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $text;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="messages")
     * @ORM\JoinColumn(referencedColumnName="customer_code", nullable=false)
     */
    public $customer;

    /**
     * @ORM\Column(type="string", length=16)
     */
    public $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
