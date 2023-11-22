<?php

namespace App\Entity;

use App\Entity\Contracts\PutProductCategoryItemInterface;
use App\Repository\ProductCategoryItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

/**
 * @ORM\Entity(repositoryClass=ProductCategoryItemRepository::class)
 * @ORM\Table(
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="ix_uq_product_category_item",
 *             columns={"product_id", "category_id"}
 *         )
 *     },
 *     indexes={
 *         @ORM\Index(name="ix_category_id", columns={"category_id"})
 *     }
 * )
 */
class ProductCategoryItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="ulid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.ulid_generator")
     */
    private Ulid $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="productCategoryItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=ProductCategory::class, inversedBy="productCategoryItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
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

    public function put(PutProductCategoryItemInterface $request): self
    {
        return $this
            ->setProduct($request->getProduct())
            ->setCategory($request->getProductCategory())
        ;
    }
}
