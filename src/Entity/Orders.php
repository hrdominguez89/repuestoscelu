<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: "orders")]
    #[ORM\JoinColumn(nullable: false)]
    private $customer;

    #[ORM\Column(type: "string", length: 255)]
    private $customer_name;

    #[ORM\Column(type: "string", length: 255)]
    private $customer_email;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $code_area_phone_customer;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $phone_customer;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $customer_identity_number;

    #[ORM\ManyToOne(targetEntity: States::class, inversedBy: "orders")]
    #[ORM\JoinColumn(nullable: true)]
    private $customer_state;

    #[ORM\ManyToOne(targetEntity: Cities::class, inversedBy: "orders")]
    #[ORM\JoinColumn(nullable: true)]
    private $customer_city;

    #[ORM\Column(type: "text", nullable: true)]
    private $customer_street_address;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $customer_postal_code;

    #[ORM\Column(type: "text", nullable: true)]
    private $customer_number_address;

    #[ORM\Column(type: "text", nullable: true)]
    private $customer_floor_apartment;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $bill_file;

    #[ORM\Column(type: "float", nullable: true)]
    private $total_order;

    #[ORM\ManyToOne(targetEntity: StatusOrderType::class, inversedBy: "orders")]
    #[ORM\JoinColumn(nullable: false)]
    private $status;

    #[ORM\OneToMany(targetEntity: OrdersProducts::class, mappedBy: "order_number", cascade: ["remove"])]
    private $ordersProducts;

    #[ORM\Column(type: "datetime", nullable: false)]
    private $created_at;

    #[ORM\OneToMany(targetEntity: PaymentsFiles::class, mappedBy: "order_number", cascade: ["remove"])]
    private $paymentsFiles;

    #[ORM\ManyToOne(inversedBy: 'number_order')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PaymentType $paymentType = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $sale_point = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modified_at = null;

    public function __construct()
    {
        $this->ordersProducts = new ArrayCollection();
        $this->paymentsFiles = new ArrayCollection();
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCustomerName(): ?string
    {
        return $this->customer_name;
    }

    public function setCustomerName(string $customer_name): self
    {
        $this->customer_name = $customer_name;

        return $this;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customer_email;
    }

    public function setCustomerEmail(string $customer_email): self
    {
        $this->customer_email = $customer_email;

        return $this;
    }

    public function getCodeAreaPhoneCustomer(): ?string
    {
        return $this->code_area_phone_customer;
    }

    public function setCodeAreaPhoneCustomer(string $code_area_phone_customer): self
    {
        $this->code_area_phone_customer = $code_area_phone_customer;

        return $this;
    }

    public function getPhoneCustomer(): ?string
    {
        return $this->phone_customer;
    }

    public function setPhoneCustomer(?string $phone_customer): self
    {
        $this->phone_customer = $phone_customer;

        return $this;
    }

    public function getCustomerIdentityNumber(): ?string
    {
        return $this->customer_identity_number;
    }

    public function setCustomerIdentityNumber(string $customer_identity_number): self
    {
        $this->customer_identity_number = $customer_identity_number;

        return $this;
    }

    public function getBillFile(): ?string
    {
        return $this->bill_file;
    }

    public function setBillFile(?string $bill_file): self
    {
        $this->bill_file = $bill_file;

        return $this;
    }

    public function getCustomerState(): ?States
    {
        return $this->customer_state;
    }

    public function setCustomerState(?States $customer_state): self
    {
        $this->customer_state = $customer_state;

        return $this;
    }

    public function getCustomerCity(): ?Cities
    {
        return $this->customer_city;
    }

    public function setCustomerCity(?Cities $customer_city): self
    {
        $this->customer_city = $customer_city;

        return $this;
    }

    public function getCustomerStreetAddress(): ?string
    {
        return $this->customer_street_address;
    }

    public function setCustomerStreetAddress(string $customer_street_address): self
    {
        $this->customer_street_address = $customer_street_address;

        return $this;
    }

    public function getCustomerPostalCode(): ?string
    {
        return $this->customer_postal_code;
    }

    public function setCustomerPostalCode(string $customer_postal_code): self
    {
        $this->customer_postal_code = $customer_postal_code;

        return $this;
    }

    public function getCustomerNumberAddress(): ?string
    {
        return $this->customer_number_address;
    }

    public function setCustomerNumberAddress(?string $customer_number_address): self
    {
        $this->customer_number_address = $customer_number_address;

        return $this;
    }

    public function getCustomerFloorApartment(): ?string
    {
        return $this->customer_floor_apartment;
    }

    public function setCustomerFloorApartment(?string $customer_floor_apartment): self
    {
        $this->customer_floor_apartment = $customer_floor_apartment;

        return $this;
    }

    public function getTotalOrder(): ?float
    {
        return $this->total_order;
    }

    public function setTotalOrder(float $total_order): self
    {
        $this->total_order = $total_order;

        return $this;
    }

    public function getStatus(): ?StatusOrderType
    {
        return $this->status;
    }

    public function setStatus(?StatusOrderType $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, OrdersProducts>
     */
    public function getOrdersProducts(): Collection
    {
        return $this->ordersProducts;
    }

    public function addOrdersProduct(OrdersProducts $ordersProduct): self
    {
        if (!$this->ordersProducts->contains($ordersProduct)) {
            $this->ordersProducts[] = $ordersProduct;
            $ordersProduct->setOrderNumber($this);
        }

        return $this;
    }

    public function removeOrdersProduct(OrdersProducts $ordersProduct): self
    {
        if ($this->ordersProducts->removeElement($ordersProduct)) {
            // set the owning side to null (unless already changed)
            if ($ordersProduct->getOrderNumber() === $this) {
                $ordersProduct->setOrderNumber(null);
            }
        }

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

    public function generateOrder()
    {
        $orders_products_array = $this->ordersProducts;
        $orders_products_result = [];
        foreach ($orders_products_array as $order_product) {
            $orders_products_result[] = [
                'product_name' => $order_product->getName(),
                'quantity' => $order_product->getQuantity(),
                'code' => $order_product->getCod(),
                'price' => $order_product->getPrice(),
            ];
        }


        $payments_files_array = $this->paymentsFiles;
        $payments_files_result = [];

        foreach ($payments_files_array as $paymentFile) {
            $payments_files_result[] = [
                "id" => $paymentFile->getId(),
                "payment_file" => $paymentFile->getPaymentFile(),
                "created_at" => $paymentFile->getCreatedAt(),
            ];
        }

        return [
            "order_id" => $this->getId(),
            "created_at" => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            "status_order_name" => $this->getStatus()->getName(),
            "status_order_id" => $this->getStatus()->getId(),
            "products" => $orders_products_result,
            "order_data" => [
                "name" => $this->getCustomerName(),
                "email" => $this->getCustomerEmail(),
                "identity_number" => $this->getCustomerIdentityNumber(),
                "code_area" => $this->getCodeAreaPhoneCustomer(),
                "phone"=> $this->getPhoneCustomer(),
                "state_id" => $this->getCustomerState()->getId(),
                "state_name" => $this->getCustomerState()->getName(),
                "city_id" => $this->getCustomerCity()->getId(),
                "city_name" => $this->getCustomerCity()->getName(),
                "postal_code" => $this->getCustomerPostalCode(),
                "street_address" => $this->getCustomerStreetAddress(),
                "number_address" => $this->getCustomerNumberAddress(),
                "floor_apartment" => $this->getCustomerFloorApartment()
            ],
            "bill_file" => $this->getBillFile(),
            "payments_files" => $payments_files_result,
            "total_order" => number_format((float)$this->getTotalOrder(), 2, ',', '.')
        ];
    }

    /**
     * @return Collection<int, PaymentsFiles>
     */
    public function getPaymentsFiles(): Collection
    {
        return $this->paymentsFiles;
    }

    public function addPaymentsFile(PaymentsFiles $paymentsFile): self
    {
        if (!$this->paymentsFiles->contains($paymentsFile)) {
            $this->paymentsFiles[] = $paymentsFile;
            $paymentsFile->setOrderNumber($this);
        }

        return $this;
    }

    public function removePaymentsFile(PaymentsFiles $paymentsFile): self
    {
        if ($this->paymentsFiles->removeElement($paymentsFile)) {
            // set the owning side to null (unless already changed)
            if ($paymentsFile->getOrderNumber() === $this) {
                $paymentsFile->setOrderNumber(null);
            }
        }

        return $this;
    }

    public function getPaymentType(): ?PaymentType
    {
        return $this->paymentType;
    }

    public function setPaymentType(?PaymentType $paymentType): static
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function getSalePoint(): ?User
    {
        return $this->sale_point;
    }

    public function setSalePoint(?User $sale_point): static
    {
        $this->sale_point = $sale_point;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modified_at;
    }

    public function setModifiedAt(?\DateTimeInterface $modified_at): static
    {
        $this->modified_at = $modified_at;

        return $this;
    }
}
