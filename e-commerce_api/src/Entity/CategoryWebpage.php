<?php

namespace App\Entity;

use App\Repository\CategoryWebpageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

/**
 * @ORM\Entity(repositoryClass=CategoryWebpageRepository::class)
 * @ORM\Table(
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="ix_uq__category_webpage",
 *             columns={"category_id", "webpage_id"}
 *         )
 *     },
 *     indexes={
 *         @ORM\Index(name="ix__category_webpage_id", columns={"webpage_id"})
 *     }
 * )
 */
class CategoryWebpage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="ulid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.ulid_generator")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=ProductCategory::class, inversedBy="categoryWebpage", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\OneToOne(targetEntity=Webpage::class, inversedBy="categoryWebpage", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $webpage;

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getCategory(): ?ProductCategory
    {
        return $this->category;
    }

    public function setCategory(ProductCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getWebpage(): ?Webpage
    {
        return $this->webpage;
    }

    public function setWebpage(Webpage $webpage): self
    {
        $this->webpage = $webpage;

        return $this;
    }
}
