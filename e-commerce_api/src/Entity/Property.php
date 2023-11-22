<?php

namespace App\Entity;

use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PropertyRepository::class)
 */
class Property
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=CategoryProperty::class, mappedBy="property")
     */
    private $categoryProperties;

    public function __construct()
    {
        $this->categoryProperties = new ArrayCollection();
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
            $categoryProperty->setProperty($this);
        }

        return $this;
    }

    public function removeCategoryProperty(CategoryProperty $categoryProperty): self
    {
        if ($this->categoryProperties->removeElement($categoryProperty)) {
            // set the owning side to null (unless already changed)
            if ($categoryProperty->getProperty() === $this) {
                $categoryProperty->setProperty(null);
            }
        }

        return $this;
    }
}
