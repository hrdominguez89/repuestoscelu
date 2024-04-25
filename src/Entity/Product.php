<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: "App\Repository\ProductRepository")]
#[ORM\Table(name: "mia_product")]
#[UniqueEntity(fields: ['sale_point', 'cod'], message: "Ya se encuentra registrado este código")]
class Product
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    protected $id;

    #[ORM\Column(name: "name", type: "string", nullable: false, length: 255)]
    protected $name;

    #[ORM\Column(name: "slug", type: "string", nullable: false, length: 255)]
    protected $slug;

    #[ORM\Column(name: "description", type: "text", nullable: true)]
    protected $description;

    #[ORM\Column(name: "created_at", type: "datetime", nullable: false)]
    protected $created_at;

    #[ORM\Column(type: "string", length: 100, nullable: "true")]
    private $cod;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: "products")]
    private $category;

    #[ORM\ManyToOne(targetEntity: Brand::class, inversedBy: "products")]
    private $brand;

    #[ORM\Column(type: "boolean", nullable: true, options: ["default" => False])]
    private $visible;

    #[ORM\OneToMany(targetEntity: ProductImages::class, mappedBy: "product", orphanRemoval: true)]
    private $image;

    #[ORM\ManyToOne(targetEntity: Subcategory::class, inversedBy: "products")]
    private $subcategory;

    #[ORM\OneToMany(targetEntity: OrdersProducts::class, mappedBy: "product")]
    private $ordersProducts;

    #[ORM\Column(type: "text", nullable: true)]
    private $long_description;

    #[ORM\OneToMany(targetEntity: FavoriteProduct::class, mappedBy: "product")]
    private $favoriteProducts;

    #[ORM\OneToMany(targetEntity: ShoppingCart::class, mappedBy: "product")]
    private $shoppingCarts;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $sale_point = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductsSalesPoints::class)]
    private Collection $productsSalesPoints;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?Colors $colors = null;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?Models $models = null;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?ScreenSize $screenSize = null;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?ScreenResolution $screenResolution = null;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?CPU $cPU = null;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?GPU $gPU = null;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?Memory $memory = null;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?Storage $storage = null;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?OS $oS = null;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->visible = false;
        $this->image = new ArrayCollection();
        $this->ordersProducts = new ArrayCollection();
        $this->favoriteProducts = new ArrayCollection();
        $this->shoppingCarts = new ArrayCollection();
        $this->productsSalesPoints = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        $slugify = new Slugify();

        $this->slug = $slugify->slugify($name);

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }


    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    /**
     * @param \DateTime $created_at
     * @return $this
     */
    public function setCreatedAt(\DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }


    public function getCod(): ?string
    {
        return strtoupper($this->cod);
    }

    public function setCod(?string $cod): self
    {
        $this->cod = strtoupper($cod);

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(?bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }


    /**
     * @return Collection<int, ProductImages>
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(ProductImages $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image[] = $image;
            $image->setProduct($this);
        }

        return $this;
    }

    public function getPrincipalImage()
    {
        $principalImage = $this->getImage()->filter(function (ProductImages $productImages) {
            return $productImages->getPrincipal();
        });
        return $principalImage->first() ? $principalImage->first()->getImage() : null;
    }

    public function removeImage(ProductImages $image): self
    {
        if ($this->image->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

        return $this;
    }

    public function getSubcategory(): ?Subcategory
    {
        return $this->subcategory;
    }

    public function setSubcategory(?Subcategory $subcategory): self
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    public function getFullDataProduct()
    {
        return [
            'id' => $this->getId(),
            'category_id' => $this->getCategory()->getId(),
            'subcategory_id' => $this->getSubcategory() ? $this->getSubcategory()->getId() : '',
            'brand_id' => $this->getBrand()->getId(),
            'cod' => $this->getCod(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
        ];
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
            $ordersProduct->setProduct($this);
        }

        return $this;
    }

    public function removeOrdersProduct(OrdersProducts $ordersProduct): self
    {
        if ($this->ordersProducts->removeElement($ordersProduct)) {
            // set the owning side to null (unless already changed)
            if ($ordersProduct->getProduct() === $this) {
                $ordersProduct->setProduct(null);
            }
        }

        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->long_description;
    }

    public function setLongDescription(?string $long_description): self
    {
        $this->long_description = $long_description;

        return $this;
    }




    public function getBasicDataProduct()
    {
        return [
            "id" => (int)$this->getId(),
            "name" => $this->getName(),
            "image" => $this->getPrincipalImage(),
        ];
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
            $favoriteProduct->setProduct($this);
        }

        return $this;
    }

    public function removeFavoriteProduct(FavoriteProduct $favoriteProduct): self
    {
        if ($this->favoriteProducts->removeElement($favoriteProduct)) {
            // set the owning side to null (unless already changed)
            if ($favoriteProduct->getProduct() === $this) {
                $favoriteProduct->setProduct(null);
            }
        }

        return $this;
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
            $shoppingCart->setProduct($this);
        }

        return $this;
    }

    public function removeShoppingCart(ShoppingCart $shoppingCart): self
    {
        if ($this->shoppingCarts->removeElement($shoppingCart)) {
            // set the owning side to null (unless already changed)
            if ($shoppingCart->getProduct() === $this) {
                $shoppingCart->setProduct(null);
            }
        }

        return $this;
    }

    public function getSalePoint(): ?user
    {
        return $this->sale_point;
    }

    public function setSalePoint(?user $sale_point): static
    {
        $this->sale_point = $sale_point;

        return $this;
    }

    /**
     * @return Collection<int, ProductsSalesPoints>
     */
    public function getProductsSalesPoints(): Collection
    {
        return $this->productsSalesPoints;
    }

    public function addProductsSalesPoint(ProductsSalesPoints $productsSalesPoint): static
    {
        if (!$this->productsSalesPoints->contains($productsSalesPoint)) {
            $this->productsSalesPoints->add($productsSalesPoint);
            $productsSalesPoint->setProduct($this);
        }

        return $this;
    }

    public function removeProductsSalesPoint(ProductsSalesPoints $productsSalesPoint): static
    {
        if ($this->productsSalesPoints->removeElement($productsSalesPoint)) {
            // set the owning side to null (unless already changed)
            if ($productsSalesPoint->getProduct() === $this) {
                $productsSalesPoint->setProduct(null);
            }
        }

        return $this;
    }

    public function getColors(): ?Colors
    {
        return $this->colors;
    }

    public function setColors(?Colors $colors): static
    {
        $this->colors = $colors;

        return $this;
    }

    public function getModels(): ?Models
    {
        return $this->models;
    }

    public function setModels(?Models $models): static
    {
        $this->models = $models;

        return $this;
    }

    public function getScreenSize(): ?ScreenSize
    {
        return $this->screenSize;
    }

    public function setScreenSize(?ScreenSize $screenSize): static
    {
        $this->screenSize = $screenSize;

        return $this;
    }

    public function getScreenResolution(): ?ScreenResolution
    {
        return $this->screenResolution;
    }

    public function setScreenResolution(?ScreenResolution $screenResolution): static
    {
        $this->screenResolution = $screenResolution;

        return $this;
    }

    public function getCPU(): ?CPU
    {
        return $this->cPU;
    }

    public function setCPU(?CPU $cPU): static
    {
        $this->cPU = $cPU;

        return $this;
    }

    public function getGPU(): ?GPU
    {
        return $this->gPU;
    }

    public function setGPU(?GPU $gPU): static
    {
        $this->gPU = $gPU;

        return $this;
    }

    public function getMemory(): ?Memory
    {
        return $this->memory;
    }

    public function setMemory(?Memory $memory): static
    {
        $this->memory = $memory;

        return $this;
    }

    public function getStorage(): ?Storage
    {
        return $this->storage;
    }

    public function setStorage(?Storage $storage): static
    {
        $this->storage = $storage;

        return $this;
    }

    public function getOS(): ?OS
    {
        return $this->oS;
    }

    public function setOS(?OS $oS): static
    {
        $this->oS = $oS;

        return $this;
    }
}
