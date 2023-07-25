<?php

namespace App\Entity;

use App\Entity\Contracts\PutProductGroupItemInterface;
use App\Repository\ProductGroupItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

/**
 * @ORM\Entity(repositoryClass=ProductGroupItemRepository::class)
 * @ORM\Table(
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="ix_uq_product_group_item",
 *             columns={"product_id", "product_group_id"}
 *         )
 *     },
 *     indexes={
 *         @ORM\Index(
 *             name="ix_product_group_item__group_id",
 *             columns={"product_group_id"}
 *         )
 *     }
 * )
 */
class ProductGroupItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="ulid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.ulid_generator")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="productGroupItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=ProductGroup::class, inversedBy="productGroupItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $productGroup;

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

    public function getProductGroup(): ?ProductGroup
    {
        return $this->productGroup;
    }

    public function setProductGroup(?ProductGroup $productGroup): self
    {
        $this->productGroup = $productGroup;

        return $this;
    }

    public function put(PutProductGroupItemInterface $request): self
    {
        return $this
            ->setProduct($request->getProduct())
            ->setProductGroup($request->getProductGroup())
        ;
    }
}
