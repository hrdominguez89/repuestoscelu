<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

 #[ORM\Entity(repositoryClass:"App\Repository\CurrencyChangeRepository")]
 #[ORM\Table(name:"mia_currency_change")]
class CurrencyChange
{
     #[ORM\Id]
     #[ORM\GeneratedValue]
     #[ORM\Column(type:"bigint")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(name: "currency_id", referencedColumnName: "id")]
    private $currency;

     #[ORM\Column(name:"value", type:"float")]
    private $value;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @param Currency $currency
     * @return $this
     */
    public function setCurrency(Currency $currency): CurrencyChange
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param float $value
     * @return $this
     */
    public function setValue(float $value): CurrencyChange
    {
        $this->value = $value;

        return $this;
    }


}
