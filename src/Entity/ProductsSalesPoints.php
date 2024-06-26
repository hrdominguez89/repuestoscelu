<?php

namespace App\Entity;

use App\Repository\ProductsSalesPointsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsSalesPointsRepository::class)]
class ProductsSalesPoints
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'productsSalesPoints')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'productsSalesPoints')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $sale_point = null;

    #[ORM\OneToMany(mappedBy: 'product_sale_point', targetEntity: ProductSalePointTag::class)]
    private Collection $productSalePointTags;

    #[ORM\OneToMany(mappedBy: 'product_sale_point', targetEntity: HistoricalPrice::class)]
    private Collection $historicalPrices;

    #[ORM\OneToMany(mappedBy: 'product_sale_point', targetEntity: ProductSalePointInventory::class)]
    private Collection $productSalePointInventories;

    #[ORM\OneToMany(targetEntity: FavoriteProduct::class, mappedBy: "productsSalesPoints")]
    private $favoriteProducts;

    #[ORM\OneToMany(targetEntity: ShoppingCart::class, mappedBy: "productsSalesPoints")]
    private $shoppingCarts;

    #[ORM\OneToMany(mappedBy: 'productsSalesPoints', targetEntity: OrdersProducts::class)]
    private Collection $ordersProducts;

    public function __construct()
    {
        $this->productSalePointTags = new ArrayCollection();
        $this->historicalPrices = new ArrayCollection();
        $this->productSalePointInventories = new ArrayCollection();
        $this->favoriteProducts = new ArrayCollection();
        $this->shoppingCarts = new ArrayCollection();
        $this->ordersProducts = new ArrayCollection();
    }

    /**
     * @return Collection<int, ShoppingCart>
     */
    public function getShoppingCarts(): Collection
    {
        return $this->shoppingCarts;
    }

    public function addShoppingCart(ShoppingCart $shoppingCart): self
    {
        if (!$this->shoppingCarts->contains($shoppingCart)) {
            $this->shoppingCarts[] = $shoppingCart;
            $shoppingCart->setProductsSalesPoints($this);
        }

        return $this;
    }

    public function removeShoppingCart(ShoppingCart $shoppingCart): self
    {
        if ($this->shoppingCarts->removeElement($shoppingCart)) {
            // set the owning side to null (unless already changed)
            if ($shoppingCart->getProductsSalesPoints() === $this) {
                $shoppingCart->setProductsSalesPoints(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, FavoriteProduct>
     */
    public function getFavoriteProducts(): Collection
    {
        return $this->favoriteProducts;
    }

    public function addFavoriteProduct(FavoriteProduct $favoriteProduct): self
    {
        if (!$this->favoriteProducts->contains($favoriteProduct)) {
            $this->favoriteProducts[] = $favoriteProduct;
            $favoriteProduct->setProductsSalesPoints($this);
        }

        return $this;
    }

    public function removeFavoriteProduct(FavoriteProduct $favoriteProduct): self
    {
        if ($this->favoriteProducts->removeElement($favoriteProduct)) {
            // set the owning side to null (unless already changed)
            if ($favoriteProduct->getProductsSalesPoints() === $this) {
                $favoriteProduct->setProductsSalesPoints(null);
            }
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

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

    /**
     * @return Collection<int, ProductSalePointTag>
     */
    public function getProductSalePointTags(): Collection
    {
        return $this->productSalePointTags;
    }

    public function addProductSalePointTag(ProductSalePointTag $productSalePointTag): static
    {
        if (!$this->productSalePointTags->contains($productSalePointTag)) {
            $this->productSalePointTags->add($productSalePointTag);
            $productSalePointTag->setProductSalePoint($this);
        }

        return $this;
    }

    public function removeProductSalePointTag(ProductSalePointTag $productSalePointTag): static
    {
        if ($this->productSalePointTags->removeElement($productSalePointTag)) {
            // set the owning side to null (unless already changed)
            if ($productSalePointTag->getProductSalePoint() === $this) {
                $productSalePointTag->setProductSalePoint(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, HistoricalPrice>
     */
    public function getHistoricalPrices(): Collection
    {
        return $this->historicalPrices;
    }

    public function addHistoricalPrice(HistoricalPrice $historicalPrice): static
    {
        if (!$this->historicalPrices->contains($historicalPrice)) {
            $this->historicalPrices->add($historicalPrice);
            $historicalPrice->setProductSalePoint($this);
        }

        return $this;
    }

    public function removeHistoricalPrice(HistoricalPrice $historicalPrice): static
    {
        if ($this->historicalPrices->removeElement($historicalPrice)) {
            // set the owning side to null (unless already changed)
            if ($historicalPrice->getProductSalePoint() === $this) {
                $historicalPrice->setProductSalePoint(null);
            }
        }

        return $this;
    }

    public function getLastPrice(): ?HistoricalPrice
    {
        return $this->historicalPrices->last() ? $this->historicalPrices->last() : NULL;
    }


    public function getDataBasicProductFront()
    {
        return [
            'id' => $this->getId(),
            'available' => $this->getLastInventory() ? $this->getLastInventory()->getAvailable() : 0,
            'image' => $this->getProduct()->getPrincipalImage(),
            'title' => $this->getProduct()->getName(),
            'price' => $this->getLastPrice() ?  number_format((float)$this->getLastPrice()->getPrice(), 2, ',', '.') : 'No definido',
            'state' => $this->getSalePoint()->getState()->getName(),
            'city' => $this->getSalePoint()->getCity()->getName()
        ];
    }

    /**
     * @return Collection<int, ProductSalePointInventory>
     */
    public function getProductSalePointInventories(): Collection
    {
        return $this->productSalePointInventories;
    }

    public function addProductSalePointInventory(ProductSalePointInventory $productSalePointInventory): static
    {
        if (!$this->productSalePointInventories->contains($productSalePointInventory)) {
            $this->productSalePointInventories->add($productSalePointInventory);
            $productSalePointInventory->setProductSalePoint($this);
        }

        return $this;
    }

    public function removeProductSalePointInventory(ProductSalePointInventory $productSalePointInventory): static
    {
        if ($this->productSalePointInventories->removeElement($productSalePointInventory)) {
            // set the owning side to null (unless already changed)
            if ($productSalePointInventory->getProductSalePoint() === $this) {
                $productSalePointInventory->setProductSalePoint(null);
            }
        }

        return $this;
    }

    public function getLastInventory(): ?ProductSalePointInventory
    {
        return $this->productSalePointInventories->last() ? $this->productSalePointInventories->last() : NULL;
    }

    /**
     * @return Collection<int, OrdersProducts>
     */
    public function getOrdersProducts(): Collection
    {
        return $this->ordersProducts;
    }

    public function addOrdersProduct(OrdersProducts $ordersProduct): static
    {
        if (!$this->ordersProducts->contains($ordersProduct)) {
            $this->ordersProducts->add($ordersProduct);
            $ordersProduct->setProductsSalesPoints($this);
        }

        return $this;
    }

    public function removeOrdersProduct(OrdersProducts $ordersProduct): static
    {
        if ($this->ordersProducts->removeElement($ordersProduct)) {
            // set the owning side to null (unless already changed)
            if ($ordersProduct->getProductsSalesPoints() === $this) {
                $ordersProduct->setProductsSalesPoints(null);
            }
        }

        return $this;
    }
}
