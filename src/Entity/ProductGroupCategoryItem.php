<?php

namespace App\Entity;

use App\Repository\ProductGroupCategoryItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

/**
 * @ORM\Table(
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *              name="ix_uq_prdct_grp_ctgry",
 *              columns={"product_group_id", "product_category_id"}
 *         )
 *     },
 *     indexes={
 *         @ORM\Index(
 *              name="ix_prdct_grp_ctgry__category_id",
 *              columns={"product_category_id"}
 *         )
 *     }
 * )
 * @ORM\Entity(repositoryClass=ProductGroupCategoryItemRepository::class)
 */
class ProductGroupCategoryItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="ulid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.ulid_generator")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ProductGroup::class, inversedBy="productGroupCategoryItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $productGroup;

    /**
     * @ORM\ManyToOne(targetEntity=ProductCategory::class, inversedBy="productGroupCategoryItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $productCategory;

    /**
     * @ORM\Column(type="smallint", options={"default":500})
     */
    private $sortOrder;

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getProductGroup(): ?ProductGroup
    {
        return $this->productGroup;
    }

    public function setProductGroup(?ProductGroup $productGroup): self
    {
        $this->productGroup = $productGroup;
        return $this;
    }

    public function getProductCategory(): ?ProductCategory
    {
        return $this->productCategory;
    }

    public function setProductCategory(?ProductCategory $productCategory): self
    {
        $this->productCategory = $productCategory;
        return $this;
    }

    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): self
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }
}
