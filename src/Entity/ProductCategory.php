<?php

namespace App\Entity;

use App\Domain\Contracts\ProductCategoryInterface;
use App\Repository\ProductCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductCategoryRepository::class)
 */
class ProductCategory
    implements ProductCategoryInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity=ProductCategory::class, inversedBy="productCategories")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=ProductCategory::class, mappedBy="parent")
     */
    private $productCategories;

    /**
     * @ORM\OneToMany(targetEntity=CategoryProperty::class, mappedBy="category")
     */
    private $categoryProperties;

    /**
     * @ORM\OneToMany(targetEntity=ProductCategoryItem::class, mappedBy="category")
     */
    private $productCategoryItems;

    /**
     * @ORM\OneToMany(targetEntity=ModelItem::class, mappedBy="productCategory")
     */
    private $modelItems;

    /**
     * @ORM\OneToOne(targetEntity=CategoryWebpage::class, mappedBy="category", cascade={"persist", "remove"})
     */
    private $categoryWebpage;

    public function __construct()
    {
        $this->productCategories = new ArrayCollection();
        $this->categoryProperties = new ArrayCollection();
        $this->productCategoryItems = new ArrayCollection();
        $this->modelItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getParent(): ?ProductCategoryInterface
    {
        return $this->parent;
    }

    public function setParent(?ProductCategoryInterface $parent): self
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getProductCategories(): Collection
    {
        return $this->productCategories;
    }

    public function addProductCategory(self $productCategory): self
    {
        if (!$this->productCategories->contains($productCategory)) {
            $this->productCategories[] = $productCategory;
            $productCategory->setParent($this);
        }

        return $this;
    }

    public function removeProductCategory(self $productCategory): self
    {
        if ($this->productCategories->removeElement($productCategory)) {
            // set the owning side to null (unless already changed)
            if ($productCategory->getParent() === $this) {
                $productCategory->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CategoryProperty>
     */
    public function getCategoryProperties(): Collection
    {
        return $this->categoryProperties;
    }

    public function addCategoryProperty(CategoryProperty $categoryProperty): self
    {
        if (!$this->categoryProperties->contains($categoryProperty)) {
            $this->categoryProperties[] = $categoryProperty;
            $categoryProperty->setCategory($this);
        }

        return $this;
    }

    public function removeCategoryProperty(CategoryProperty $categoryProperty): self
    {
        if ($this->categoryProperties->removeElement($categoryProperty)) {
            // set the owning side to null (unless already changed)
            if ($categoryProperty->getCategory() === $this) {
                $categoryProperty->setCategory(null);
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
            $productCategoryItem->setCategory($this);
        }

        return $this;
    }

    public function removeProductCategoryItem(ProductCategoryItem $productCategoryItem): self
    {
        if ($this->productCategoryItems->removeElement($productCategoryItem)) {
            // set the owning side to null (unless already changed)
            if ($productCategoryItem->getCategory() === $this) {
                $productCategoryItem->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ModelItem>
     */
    public function getModelItems(): Collection
    {
        return $this->modelItems;
    }

    public function addModelItem(ModelItem $modelItem): self
    {
        if (!$this->modelItems->contains($modelItem)) {
            $this->modelItems[] = $modelItem;
            $modelItem->setProductCategory($this);
        }

        return $this;
    }

    public function removeModelItem(ModelItem $modelItem): self
    {
        if ($this->modelItems->removeElement($modelItem)) {
            // set the owning side to null (unless already changed)
            if ($modelItem->getProductCategory() === $this) {
                $modelItem->setProductCategory(null);
            }
        }

        return $this;
    }

    public function getCategoryWebpage(): ?CategoryWebpage
    {
        return $this->categoryWebpage;
    }

    public function setCategoryWebpage(CategoryWebpage $categoryWebpage): self
    {
        // set the owning side of the relation if necessary
        if ($categoryWebpage->getCategory() !== $this) {
            $categoryWebpage->setCategory($this);
        }

        $this->categoryWebpage = $categoryWebpage;

        return $this;
    }


}
