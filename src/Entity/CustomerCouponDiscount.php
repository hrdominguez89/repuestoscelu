<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\CustomerCouponDiscountRepository")]
#[ORM\Table(name: "mia_customer_coupon_discount")]
class CustomerCouponDiscount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: "couponDiscounts", cascade: ["persist"])]
    #[ORM\JoinColumn(name: "customer_id", referencedColumnName: "id", nullable: false)]
    private $customer;

    #[ORM\Column(name: "percent", type: "boolean")]
    private $percent;

    #[ORM\Column(name: "discount", type: "float")]
    private $discount;

    #[ORM\Column(name: "coupon", type: "string", length: 255)]
    private $coupon;

    #[ORM\Column(name: "date_apply", type: "datetime")]
    private $dateApply;

    #[ORM\Column(name: "applied", type: "boolean")]
    private $applied;

    public function __construct(Customer $customer, CouponDiscount $couponDiscount)
    {
        $this->setCustomer($customer);

        $this->percent = $couponDiscount->isPercent();
        $this->discount = $couponDiscount->getValue();
        $this->coupon = $couponDiscount->getNro();

        $this->dateApply = new \DateTime();
        $this->applied = false;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoupon(): ?string
    {
        return $this->coupon;
    }

    public function setCoupon(string $coupon): CustomerCouponDiscount
    {
        $this->coupon = $coupon;

        return $this;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): CustomerCouponDiscount
    {
        $this->customer = $customer;

        $customer->addCustomerCouponDiscount($this);

        return $this;
    }

    public function isPercent(): bool
    {
        return $this->percent;
    }

    public function setPercent(bool $percent): CustomerCouponDiscount
    {
        $this->percent = $percent;

        return $this;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function setDiscount(float $discount): CustomerCouponDiscount
    {
        $this->discount = $discount;

        return $this;
    }

    public function getDateApply(): \DateTime
    {
        return $this->dateApply;
    }

    public function setDateApply(\DateTime $dateApply): CustomerCouponDiscount
    {
        $this->dateApply = $dateApply;

        return $this;
    }

    public function isApplied(): bool
    {
        return $this->applied;
    }

    public function setApplied(bool $applied): CustomerCouponDiscount
    {
        $this->applied = $applied;

        return $this;
    }

    public function asArray(): array
    {
        return [
            "title" => "Cupón de descuento",
            "price" => $this->getDiscount(),
            "type" => $this->isPercent() ? "percent" : "discount",
        ];
    }

    public function getPercent(): ?bool
    {
        return $this->percent;
    }

    public function getApplied(): ?bool
    {
        return $this->applied;
    }
}
