<?php

namespace App\Entity;

use App\Repository\PaymentsReceivedFilesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaymentsReceivedFilesRepository::class)
 */
class PaymentsReceivedFiles
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Orders::class, inversedBy="paymentsReceivedFiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $order_number;

    /**
     * @ORM\Column(type="text")
     */
    private $payment_received_file;

    /**
     * @ORM\Column(type="datetime", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created_at;

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

    public function getPaymentReceivedFile(): ?string
    {
        return $this->payment_received_file;
    }

    public function setPaymentReceivedFile(string $payment_received_file): self
    {
        $this->payment_received_file = $payment_received_file;

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
}
