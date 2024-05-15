<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $bill_file;

    #[ORM\ManyToOne(targetEntity: States::class, inversedBy: "orders")]
    #[ORM\JoinColumn(nullable: true)]
    private $bill_state;

    #[ORM\ManyToOne(targetEntity: Cities::class, inversedBy: "orders")]
    #[ORM\JoinColumn(nullable: true)]
    private $bill_city;

    #[ORM\Column(type: "text", nullable: true)]
    private $bill_address_order;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $bill_postal_code;

    #[ORM\Column(type: "text", nullable: true)]
    private $bill_additional_info;

    #[ORM\Column(type: "float", nullable: true)]
    private $subtotal;

    #[ORM\Column(type: "float", nullable: true)]
    private $total_product_discount;

    #[ORM\Column(type: "float", nullable: true)]
    private $promotional_code_discount;

    #[ORM\Column(type: "float", nullable: true)]
    private $tax;

    #[ORM\Column(type: "float", nullable: true)]
    private $shipping_cost;

    #[ORM\Column(type: "float", nullable: true)]
    private $shipping_discount;

    #[ORM\Column(type: "float", nullable: true)]
    private $paypal_service_cost;

    #[ORM\Column(type: "float", nullable: true)]
    private $total_order;

    #[ORM\ManyToOne(targetEntity: StatusOrderType::class, inversedBy: "orders")]
    #[ORM\JoinColumn(nullable: false)]
    private $status;

    #[ORM\OneToMany(targetEntity: OrdersProducts::class, mappedBy: "number_order", cascade: ["remove"])]
    private $ordersProducts;

    #[ORM\Column(type: "datetime", nullable: false)]
    private $created_at;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $receiver_name;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $receiver_document_type;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $receiver_document;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $receiver_phone_cell;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $receiver_phone_home;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $receiver_email;

    #[ORM\ManyToOne(targetEntity: States::class, inversedBy: "receiver_orders")]
    #[ORM\JoinColumn(nullable: true)]
    private $receiver_state;

    #[ORM\ManyToOne(targetEntity: Cities::class, inversedBy: "receiver_orders")]
    #[ORM\JoinColumn(nullable: true)]
    private $receiver_city;

    #[ORM\Column(type: "text", nullable: true)]
    private $receiver_address;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $receiver_cod_zip;

    #[ORM\Column(type: "text", nullable: true)]
    private $receiver_additional_info;

    #[ORM\OneToMany(targetEntity: PaymentsFiles::class, mappedBy: "order_number", cascade: ["remove"])]
    private $paymentsFiles;

    #[ORM\OneToMany(targetEntity: PaymentsReceivedFiles::class, mappedBy: "order_number", cascade: ["remove"])]
    private $paymentsReceivedFiles;

    #[ORM\OneToMany(targetEntity: DebitCreditNotesFiles::class, mappedBy: "number_order", cascade: ["remove"])]
    private $debitCreditNotesFiles;

    public function __construct()
    {
        $this->ordersProducts = new ArrayCollection();
        $this->paymentsFiles = new ArrayCollection();
        $this->paymentsReceivedFiles = new ArrayCollection();
        $this->debitCreditNotesFiles = new ArrayCollection();
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

    public function getBillState(): ?States
    {
        return $this->bill_state;
    }

    public function setBillState(?States $bill_state): self
    {
        $this->bill_state = $bill_state;

        return $this;
    }

    public function getBillCity(): ?Cities
    {
        return $this->bill_city;
    }

    public function setBillCity(?Cities $bill_city): self
    {
        $this->bill_city = $bill_city;

        return $this;
    }

    public function getBillAddressOrder(): ?string
    {
        return $this->bill_address_order;
    }

    public function setBillAddressOrder(string $bill_address_order): self
    {
        $this->bill_address_order = $bill_address_order;

        return $this;
    }

    public function getBillPostalCode(): ?string
    {
        return $this->bill_postal_code;
    }

    public function setBillPostalCode(string $bill_postal_code): self
    {
        $this->bill_postal_code = $bill_postal_code;

        return $this;
    }

    public function getBillAdditionalInfo(): ?string
    {
        return $this->bill_additional_info;
    }

    public function setBillAdditionalInfo(?string $bill_additional_info): self
    {
        $this->bill_additional_info = $bill_additional_info;

        return $this;
    }

    public function getSubtotal(): ?float
    {
        return $this->subtotal;
    }

    public function setSubtotal(float $subtotal): self
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    public function getTotalProductDiscount(): ?float
    {
        return $this->total_product_discount;
    }

    public function setTotalProductDiscount(float $total_product_discount): self
    {
        $this->total_product_discount = $total_product_discount;

        return $this;
    }

    public function getPromotionalCodeDiscount(): ?float
    {
        return $this->promotional_code_discount;
    }

    public function setPromotionalCodeDiscount(float $promotional_code_discount): self
    {
        $this->promotional_code_discount = $promotional_code_discount;

        return $this;
    }

    public function getTax(): ?float
    {
        return $this->tax;
    }

    public function setTax(float $tax): self
    {
        $this->tax = $tax;

        return $this;
    }

    public function getShippingCost(): ?float
    {
        return $this->shipping_cost;
    }

    public function setShippingCost(float $shipping_cost): self
    {
        $this->shipping_cost = $shipping_cost;

        return $this;
    }

    public function getShippingDiscount(): ?float
    {
        return $this->shipping_discount;
    }

    public function setShippingDiscount(float $shipping_discount): self
    {
        $this->shipping_discount = $shipping_discount;

        return $this;
    }

    public function getPaypalServiceCost(): ?float
    {
        return $this->paypal_service_cost;
    }

    public function setPaypalServiceCost(float $paypal_service_cost): self
    {
        $this->paypal_service_cost = $paypal_service_cost;

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
            $ordersProduct->setNumberOrder($this);
        }

        return $this;
    }

    public function removeOrdersProduct(OrdersProducts $ordersProduct): self
    {
        if ($this->ordersProducts->removeElement($ordersProduct)) {
            // set the owning side to null (unless already changed)
            if ($ordersProduct->getNumberOrder() === $this) {
                $ordersProduct->setNumberOrder(null);
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

    public function getReceiverName(): ?string
    {
        return $this->receiver_name;
    }

    public function setReceiverName(string $receiver_name): self
    {
        $this->receiver_name = $receiver_name;

        return $this;
    }

    public function getReceiverDocumentType(): ?string
    {
        return $this->receiver_document_type;
    }

    public function setReceiverDocumentType(string $receiver_document_type): self
    {
        $this->receiver_document_type = $receiver_document_type;

        return $this;
    }

    public function getReceiverDocument(): ?string
    {
        return $this->receiver_document;
    }

    public function setReceiverDocument(string $receiver_document): self
    {
        $this->receiver_document = $receiver_document;

        return $this;
    }

    public function getReceiverPhoneCell(): ?string
    {
        return $this->receiver_phone_cell;
    }

    public function setReceiverPhoneCell(string $receiver_phone_cell): self
    {
        $this->receiver_phone_cell = $receiver_phone_cell;

        return $this;
    }

    public function getReceiverPhoneHome(): ?string
    {
        return $this->receiver_phone_home;
    }

    public function setReceiverPhoneHome(?string $receiver_phone_home): self
    {
        $this->receiver_phone_home = $receiver_phone_home;

        return $this;
    }

    public function getReceiverEmail(): ?string
    {
        return $this->receiver_email;
    }

    public function setReceiverEmail(string $receiver_email): self
    {
        $this->receiver_email = $receiver_email;

        return $this;
    }

    public function getReceiverState(): ?States
    {
        return $this->receiver_state;
    }

    public function setReceiverState(?States $receiver_state): self
    {
        $this->receiver_state = $receiver_state;

        return $this;
    }

    public function getReceiverCity(): ?Cities
    {
        return $this->receiver_city;
    }

    public function setReceiverCity(?Cities $receiver_city): self
    {
        $this->receiver_city = $receiver_city;

        return $this;
    }

    public function getReceiverAddress(): ?string
    {
        return $this->receiver_address;
    }

    public function setReceiverAddress(string $receiver_address): self
    {
        $this->receiver_address = $receiver_address;

        return $this;
    }

    public function getReceiverCodZip(): ?string
    {
        return $this->receiver_cod_zip;
    }

    public function setReceiverCodZip(string $receiver_cod_zip): self
    {
        $this->receiver_cod_zip = $receiver_cod_zip;

        return $this;
    }

    public function getReceiverAdditionalInfo(): ?string
    {
        return $this->receiver_additional_info;
    }

    public function setReceiverAdditionalInfo(string $receiver_additional_info): self
    {
        $this->receiver_additional_info = $receiver_additional_info;

        return $this;
    }

    public function generateOrderToCRM()
    {
        $orders_products_array = $this->ordersProducts;
        $orders_products_result = [];

        foreach ($orders_products_array as $order_product) {
            $orders_products_result[] = [
                'product_id' => $order_product->getProduct()->getId3pl(),
                'product_name' => $order_product->getProduct()->getName(),
                'qty' => $order_product->getQuantity(),
                'weight' => $order_product->getWeight(),
                'price' => $order_product->getPrice(),
                'discount' => $order_product->getDiscount()
            ];
        }


        $payments_files_array = $this->paymentsFiles;
        $payments_files_result = [];

        foreach ($payments_files_array as $paymentFile) {
            $payments_files_result[] = [
                "payment_file" => $paymentFile->getPaymentFile(),
            ];
        }

        $payments_received_files_array = $this->paymentsReceivedFiles;
        $payments_received_files_result = [];

        foreach ($payments_received_files_array as $paymentReceivedFile) {
            $payments_received_files_result[] = [
                "payment_received_file" => $paymentReceivedFile->getPaymentReceivedFile(),
            ];
        }

        $debit_credite_notes_files_array = $this->debitCreditNotesFiles;
        $debit_credite_notes_files_result = [];

        foreach ($debit_credite_notes_files_array as $debitCreditNoteFile) {
            $debit_credite_notes_files_result[] = [
                "debit_credit_note_file" => $debitCreditNoteFile->getDebitCreditNoteFile(),
            ];
        }


        return [
            "order_id" => $this->getId(),
            "created_at" => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            "status_order" => $this->getStatus()->getId(),
            "items" => $orders_products_result,
            "customer" => [
                "id" => $this->getCustomer()->getId(),
                "name" => $this->getCustomerName(),
                "email" => $this->getCustomerEmail(),
                "code_area_phone_customer" => $this->getCodeAreaPhoneCustomer(),
                "phone_customer" => $this->getPhoneCustomer(),
                "customer_identity_number" => $this->getCustomerIdentityNumber()
            ],
            "bill_file" => $this->getBillFile(),
            "payments_files" => $payments_files_result,
            "payments_received_files" => $payments_received_files_result,
            "debit_credit_notes_files" => $debit_credite_notes_files_result,
            "receiver" => [
                "name" => $this->getReceiverName(),
                "document_type" => $this->getReceiverDocumentType(),
                "document" => $this->getReceiverDocument(),
                "phone_cell" => $this->getReceiverPhoneCell(),
                "phone_home" => $this->getReceiverPhoneHome(),
                "email" => $this->getReceiverEmail(),
                "state_id" => $this->getReceiverState() ? $this->getReceiverState()->getId() : null,
                "city_id" => $this->getReceiverCity() ? $this->getReceiverCity()->getId() : null,
                "address" => $this->getReceiverAddress(),
                "cod_zip" => $this->getReceiverCodZip(),
                "additional_info" => $this->getReceiverAdditionalInfo()
            ],
            "bill_address" => [
                "state_id" => $this->getBillState() ? $this->getBillState()->getId() : null,
                "city_id" => $this->getBillCity() ? $this->getBillCity()->getId() : null,
                "address" => $this->getBillAddressOrder(),
                "cod_zip" => $this->getBillPostalCode()
            ],
            "subtotal" => $this->getSubtotal(),
            "total_product_discount" => $this->getTotalProductDiscount(),
            "promotional_code_discount" => $this->getPromotionalCodeDiscount(),
            "tax" => $this->getTax(),
            "shipping_cost" => $this->getShippingCost(),
            "shipping_discount" => $this->getShippingDiscount(),
            "total_order" => $this->getTotalOrder()
        ];
    }

    public function getenerateOrderToFront()
    {

        $orders_products_array = $this->ordersProducts;
        $orders_products_result = [];

        foreach ($orders_products_array as $order_product) {
            $orders_products_result[] = [
                'id' => $order_product->getProduct()->getId3pl(),
                'name' => $order_product->getProduct()->getName(),
                'quantity' => $order_product->getQuantity(),
                'price' => $order_product->getPrice(),
                'discount' => $order_product->getDiscount()
            ];
        }

        // {
        //     "items": [
        //         {
        //             "id": 10,
        //             "name": "Prueba producto 1",
        //             "quantity": 5,
        //             "price": 10,
        //             "0": 25
        //         },
        //         {
        //             "id": 11,
        //             "name": "Prueba producto 2",
        //             "quantity": 4,
        //             "price": 20,
        //             "0": 25
        //         },
        //         {
        //             "id": 13,
        //             "name": "Prueba producto 3",
        //             "quantity": 1,
        //             "price": 300,
        //             "0": 25
        //         }
        //     ],
        //     "bill_data": {
        //         "identity_type": "DNI",
        //         "identity_number": "34987273",
        //         "country_id": 11,
        //         "country_name": "Argentina",
        //         "state_id": 4545,
        //         "state_name": "Buenos Aires",
        //         "city_id": 42022,
        //         "city_name": "Ciudad Autonoma de Buenos Aires",
        //         "code_zip" : "abc123",
        //         "additional_info": "informacion adicional",
        //         "address": "Calle 123 4to A"
        //     },
        //     "recipients": [
        //         {
        //             "recipient_id": 1,
        //             "country_name": "Argentina",
        //             "state_name": "Córdoba",
        //             "city_name": "Cosquin",
        //             "recipient_name": "Destinatario prueba 1",
        //             "address": "Direccion destinatario 1 23233",
        //             "recipient_phone": "1163549766"
        //         },
        //         {
        //             "recipient_id": 2,
        //             "country_name": "Argentina",
        //             "state_name": "Córdoba",
        //             "city_name": "La falda",
        //             "recipient_name": "Destinatario prueba 2",
        //             "address": "Direccion destinatario 2 23233",
        //             "recipient_phone": "1163549766"
        //         },
        //         {
        //             "recipient_id": 3,
        //             "country_name": "Argentina",
        //             "state_name": "Córdoba",
        //             "city_name": "Córdoba Capital",
        //             "recipient_name": "Destinatario prueba 3",
        //             "address": "Direccion destinatario 3 23233",
        //             "recipient_phone": "1163549766"
        //         }
        //     ]
        // }
        return [];
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

    /**
     * @return Collection<int, PaymentsReceivedFiles>
     */
    public function getPaymentsReceivedFiles(): Collection
    {
        return $this->paymentsReceivedFiles;
    }

    public function addPaymentsReceivedFile(PaymentsReceivedFiles $paymentsReceivedFile): self
    {
        if (!$this->paymentsReceivedFiles->contains($paymentsReceivedFile)) {
            $this->paymentsReceivedFiles[] = $paymentsReceivedFile;
            $paymentsReceivedFile->setOrderNumber($this);
        }

        return $this;
    }

    public function removePaymentsReceivedFile(PaymentsReceivedFiles $paymentsReceivedFile): self
    {
        if ($this->paymentsReceivedFiles->removeElement($paymentsReceivedFile)) {
            // set the owning side to null (unless already changed)
            if ($paymentsReceivedFile->getOrderNumber() === $this) {
                $paymentsReceivedFile->setOrderNumber(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DebitCreditNotesFiles>
     */
    public function getDebitCreditNotesFiles(): Collection
    {
        return $this->debitCreditNotesFiles;
    }

    public function addDebitCreditNotesFile(DebitCreditNotesFiles $debitCreditNotesFile): self
    {
        if (!$this->debitCreditNotesFiles->contains($debitCreditNotesFile)) {
            $this->debitCreditNotesFiles[] = $debitCreditNotesFile;
            $debitCreditNotesFile->setNumberOrder($this);
        }

        return $this;
    }

    public function removeDebitCreditNotesFile(DebitCreditNotesFiles $debitCreditNotesFile): self
    {
        if ($this->debitCreditNotesFiles->removeElement($debitCreditNotesFile)) {
            // set the owning side to null (unless already changed)
            if ($debitCreditNotesFile->getNumberOrder() === $this) {
                $debitCreditNotesFile->setNumberOrder(null);
            }
        }

        return $this;
    }
}
