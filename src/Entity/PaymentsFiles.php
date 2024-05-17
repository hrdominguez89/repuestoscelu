<?php

namespace App\Entity;

use App\Repository\PaymentsFilesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentsFilesRepository::class)]
class PaymentsFiles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Orders::class, inversedBy: "paymentsFiles")]
    #[ORM\JoinColumn(nullable: false)]
    private $order_number;

    #[ORM\Column(type: "text")]
    private $payment_file;

    #[ORM\Column(type: "datetime", nullable: false)]
    private $created_at;

    #[ORM\Column(options: ["default" => 0])]
    private ?float $amount = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_paid = null;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderNumber(): ?Orders
    {
        return $this->order_number;
    }

    public function setOrderNumber(?Orders $order_number): self
    {
        $this->order_number = $order_number;

        return $this;
    }

    public function getPaymentFile(): ?string
    {
        return $this->payment_file;
    }

    public function setPaymentFile(string $payment_file): self
    {
        $this->payment_file = $payment_file;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDatePaid(): ?\DateTimeInterface
    {
        return $this->date_paid;
    }

    public function setDatePaid(\DateTimeInterface $date_paid): static
    {
        $this->date_paid = $date_paid;

        return $this;
    }
}
