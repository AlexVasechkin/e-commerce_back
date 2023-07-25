<?php

namespace App\Entity;

use App\Entity\Contracts\PutProductGroupInterface;
use App\Repository\ProductGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductGroupRepository::class)
 * @ORM\Table(
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="ix_uq_product_group__name",
 *             columns={"name"}
 *         )
 *     }
 * )
 */
class ProductGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=ProductGroupItem::class, mappedBy="productGroup")
     */
    private $productGroupItems;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $isToHomepage;

    /**
     * @ORM\Column(type="integer", options={"default":500})
     */
    private $homepageSort;

    /**
     * @ORM\OneToMany(targetEntity=ProductGroupCategoryItem::class, mappedBy="productGroup")
     */
    private $productGroupCategoryItems;

    public function __construct()
    {
        $this->productGroupItems = new ArrayCollection();
        $this->productGroupCategoryItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
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

    public function put(PutProductGroupInterface $request): self
    {
        return $this
            ->setName($request->getName())
            ->setIsToHomepage($request->isToHomepage())
            ->setHomepageSort($request->getHomepageSort())
        ;
    }

    /**
     * @return Collection<int, ProductGroupItem>
     */
    public function getProductGroupItems(): Collection
    {
        return $this->productGroupItems;
    }

    public function addProductGroupItem(ProductGroupItem $productGroupItem): self
    {
        if (!$this->productGroupItems->contains($productGroupItem)) {
            $this->productGroupItems[] = $productGroupItem;
            $productGroupItem->setProductGroup($this);
        }

        return $this;
    }

    public function removeProductGroupItem(ProductGroupItem $productGroupItem): self
    {
        if ($this->productGroupItems->removeElement($productGroupItem)) {
            // set the owning side to null (unless already changed)
            if ($productGroupItem->getProductGroup() === $this) {
                $productGroupItem->setProductGroup(null);
            }
        }

        return $this;
    }

    public function isIsToHomepage(): ?bool
    {
        return $this->isToHomepage;
    }

    public function setIsToHomepage(?bool $isToHomepage): self
    {
        $this->isToHomepage = $isToHomepage;
        return $this;
    }

    public function getHomepageSort(): ?int
    {
        return $this->homepageSort;
    }

    public function setHomepageSort(int $homepageSort): self
    {
        $this->homepageSort = $homepageSort;
        return $this;
    }

    /**
     * @return Collection<int, ProductGroupCategoryItem>
     */
    public function getProductGroupCategoryItems(): Collection
    {
        return $this->productGroupCategoryItems;
    }

    public function addProductGroupCategoryItem(ProductGroupCategoryItem $productGroupCategoryItem): self
    {
        if (!$this->productGroupCategoryItems->contains($productGroupCategoryItem)) {
            $this->productGroupCategoryItems[] = $productGroupCategoryItem;
            $productGroupCategoryItem->setProductGroup($this);
        }

        return $this;
    }

    public function removeProductGroupCategoryItem(ProductGroupCategoryItem $productGroupCategoryItem): self
    {
        if ($this->productGroupCategoryItems->removeElement($productGroupCategoryItem)) {
            // set the owning side to null (unless already changed)
            if ($productGroupCategoryItem->getProductGroup() === $this) {
                $productGroupCategoryItem->setProductGroup(null);
            }
        }

        return $this;
    }
}
