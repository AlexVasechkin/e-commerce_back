<?php

namespace App\Entity;

use App\Domain\Contracts\ProductInterface;
use App\Domain\Contracts\VendorInterface;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="ix_uq_product", columns={"code", "vendor_id"})
 *     }
 * )
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
    implements ProductInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint", options={"unsigned":true})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity=Vendor::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vendor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $length;

    /**
     * @ORM\Column(type="float")
     */
    private $width;

    /**
     * @ORM\Column(type="float")
     */
    private $height;

    /**
     * @ORM\Column(type="float")
     */
    private $mass;

    /**
     * @ORM\OneToMany(targetEntity=ProductImage::class, mappedBy="product")
     */
    private $productImages;

    /**
     * @ORM\OneToMany(targetEntity=ProductCategoryItem::class, mappedBy="product")
     */
    private $productCategoryItems;

    /**
     * @ORM\OneToMany(targetEntity=ProductPropertyValue::class, mappedBy="product")
     */
    private $productPropertyValues;

    /**
     * @ORM\OneToOne(targetEntity=ModelItem::class, mappedBy="product", cascade={"persist", "remove"})
     */
    private $modelItem;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"unsigned":true, "default":0})
     */
    private $count;

    /**
     * @ORM\OneToOne(targetEntity=ProductWebpage::class, mappedBy="product", cascade={"persist", "remove"})
     */
    private $productWebpage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;

    public function __construct()
    {
        $this->productImages = new ArrayCollection();
        $this->productCategoryItems = new ArrayCollection();
        $this->productPropertyValues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getVendor(): ?VendorInterface
    {
        return $this->vendor;
    }

    public function setVendor(?VendorInterface $vendor): self
    {
        $this->vendor = $vendor;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLength(): ?float
    {
        return $this->length;
    }

    public function setLength(float $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(float $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getMass(): ?float
    {
        return $this->mass;
    }

    public function setMass(float $mass): self
    {
        $this->mass = $mass;

        return $this;
    }

    /**
     * @return Collection<int, ProductImage>
     */
    public function getProductImages(): Collection
    {
        return $this->productImages;
    }

    public function addProductImage(ProductImage $productImage): self
    {
        if (!$this->productImages->contains($productImage)) {
            $this->productImages[] = $productImage;
            $productImage->setProduct($this);
        }

        return $this;
    }

    public function removeProductImage(ProductImage $productImage): self
    {
        if ($this->productImages->removeElement($productImage)) {
            // set the owning side to null (unless already changed)
            if ($productImage->getProduct() === $this) {
                $productImage->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductCategoryItem>
     */
    public function getProductCategoryItems(): Collection
    {
        return $this->productCategoryItems;
    }

    public function addProductCategoryItem(ProductCategoryItem $productCategoryItem): self
    {
        if (!$this->productCategoryItems->contains($productCategoryItem)) {
            $this->productCategoryItems[] = $productCategoryItem;
            $productCategoryItem->setProduct($this);
        }

        return $this;
    }

    public function removeProductCategoryItem(ProductCategoryItem $productCategoryItem): self
    {
        if ($this->productCategoryItems->removeElement($productCategoryItem)) {
            // set the owning side to null (unless already changed)
            if ($productCategoryItem->getProduct() === $this) {
                $productCategoryItem->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductPropertyValue>
     */
    public function getProductPropertyValues(): Collection
    {
        return $this->productPropertyValues;
    }

    public function addProductPropertyValue(ProductPropertyValue $productPropertyValue): self
    {
        if (!$this->productPropertyValues->contains($productPropertyValue)) {
            $this->productPropertyValues[] = $productPropertyValue;
            $productPropertyValue->setProduct($this);
        }

        return $this;
    }

    public function removeProductPropertyValue(ProductPropertyValue $productPropertyValue): self
    {
        if ($this->productPropertyValues->removeElement($productPropertyValue)) {
            // set the owning side to null (unless already changed)
            if ($productPropertyValue->getProduct() === $this) {
                $productPropertyValue->setProduct(null);
            }
        }

        return $this;
    }

    public function getModelItem(): ?ModelItem
    {
        return $this->modelItem;
    }

    public function setModelItem(ModelItem $modelItem): self
    {
        // set the owning side of the relation if necessary
        if ($modelItem->getProduct() !== $this) {
            $modelItem->setProduct($this);
        }

        $this->modelItem = $modelItem;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getProductWebpage(): ?ProductWebpage
    {
        return $this->productWebpage;
    }

    public function setProductWebpage(ProductWebpage $productWebpage): self
    {
        // set the owning side of the relation if necessary
        if ($productWebpage->getProduct() !== $this) {
            $productWebpage->setProduct($this);
        }

        $this->productWebpage = $productWebpage;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
