<?php

namespace App\Entity;

use App\Repository\CategoryPropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryPropertyRepository::class)
 * @ORM\Table(
 *     indexes={
 *         @ORM\Index(name="ix_category_property", columns={"property_id", "category_id"}),
 *         @ORM\Index(name="ix_category", columns={"category_id"})
 *     }
 * )
 */
class CategoryProperty
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ProductCategory::class, inversedBy="categoryProperties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Property::class, inversedBy="categoryProperties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $property;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $type;

    /**
     * @ORM\Column(type="boolean", options={"default":true})
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity=ProductPropertyValue::class, mappedBy="property")
     */
    private $productPropertyValues;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $unit;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $groupName;

    public function __construct()
    {
        $this->productPropertyValues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?ProductCategory
    {
        return $this->category;
    }

    public function setCategory(?ProductCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): self
    {
        $this->property = $property;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

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
            $productPropertyValue->setProperty($this);
        }

        return $this;
    }

    public function removeProductPropertyValue(ProductPropertyValue $productPropertyValue): self
    {
        if ($this->productPropertyValues->removeElement($productPropertyValue)) {
            // set the owning side to null (unless already changed)
            if ($productPropertyValue->getProperty() === $this) {
                $productPropertyValue->setProperty(null);
            }
        }

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function setGroupName(?string $groupName): self
    {
        $this->groupName = $groupName;

        return $this;
    }
}
