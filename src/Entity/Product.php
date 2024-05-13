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

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $sale_point = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductsSalesPoints::class)]
    private Collection $productsSalesPoints;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?Color $color = null;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?Model $model = null;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?ScreenSize $screenSize = null;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?ScreenResolution $screenResolution = null;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?CPU $CPU = null;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?GPU $GPU = null;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?Memory $memory = null;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?Storage $storage = null;

    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?OS $OS = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: StockProduct::class)]
    private Collection $stockProducts;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductAdminInventory::class)]
    private Collection $productAdminInventories;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductDispatch::class)]
    private Collection $productDispatches;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->visible = false;
        $this->image = new ArrayCollection();
        $this->ordersProducts = new ArrayCollection();
        $this->shoppingCarts = new ArrayCollection();
        $this->productsSalesPoints = new ArrayCollection();
        $this->stockProducts = new ArrayCollection();
        $this->productAdminInventories = new ArrayCollection();
        $this->productDispatches = new ArrayCollection();
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

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(?Color $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): static
    {
        $this->model = $model;

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
        return $this->CPU;
    }

    public function setCPU(?CPU $CPU): static
    {
        $this->CPU = $CPU;

        return $this;
    }

    public function getGPU(): ?GPU
    {
        return $this->GPU;
    }

    public function setGPU(?GPU $GPU): static
    {
        $this->GPU = $GPU;

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
        return $this->OS;
    }

    public function setOS(?OS $OS): static
    {
        $this->OS = $OS;

        return $this;
    }

    /**
     * @return Collection<int, StockProduct>
     */
    public function getStockProducts(): Collection
    {
        return $this->stockProducts;
    }

    public function addStockProduct(StockProduct $stockProduct): static
    {
        if (!$this->stockProducts->contains($stockProduct)) {
            $this->stockProducts->add($stockProduct);
            $stockProduct->setProduct($this);
        }

        return $this;
    }

    public function removeStockProduct(StockProduct $stockProduct): static
    {
        if ($this->stockProducts->removeElement($stockProduct)) {
            // set the owning side to null (unless already changed)
            if ($stockProduct->getProduct() === $this) {
                $stockProduct->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductAdminInventory>
     */
    public function getProductAdminInventories(): Collection
    {
        return $this->productAdminInventories;
    }

    public function addProductAdminInventory(ProductAdminInventory $productAdminInventory): static
    {
        if (!$this->productAdminInventories->contains($productAdminInventory)) {
            $this->productAdminInventories->add($productAdminInventory);
            $productAdminInventory->setProduct($this);
        }

        return $this;
    }

    public function removeProductAdminInventory(ProductAdminInventory $productAdminInventory): static
    {
        if ($this->productAdminInventories->removeElement($productAdminInventory)) {
            // set the owning side to null (unless already changed)
            if ($productAdminInventory->getProduct() === $this) {
                $productAdminInventory->setProduct(null);
            }
        }

        return $this;
    }
    
    public function getLastInventory(): ?ProductAdminInventory
    {
        return $this->productAdminInventories->last() ? $this->productAdminInventories->last() : NULL;
    }

    /**
     * @return Collection<int, ProductDispatch>
     */
    public function getProductDispatches(): Collection
    {
        return $this->productDispatches;
    }

    public function addProductDispatch(ProductDispatch $productDispatch): static
    {
        if (!$this->productDispatches->contains($productDispatch)) {
            $this->productDispatches->add($productDispatch);
            $productDispatch->setProduct($this);
        }

        return $this;
    }

    public function removeProductDispatch(ProductDispatch $productDispatch): static
    {
        if ($this->productDispatches->removeElement($productDispatch)) {
            // set the owning side to null (unless already changed)
            if ($productDispatch->getProduct() === $this) {
                $productDispatch->setProduct(null);
            }
        }

        return $this;
    }
}
