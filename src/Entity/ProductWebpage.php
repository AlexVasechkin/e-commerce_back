<?php

namespace App\Entity;

use App\Repository\ProductWebpageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

/**
 * @ORM\Entity(repositoryClass=ProductWebpageRepository::class)
 * @ORM\Table(
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="ix_uq__product_webpage",
 *             columns={"product_id", "webpage_id"}
 *         )
 *     },
 *     indexes={
 *         @ORM\Index(name="ix__product_webpage_id", columns={"webpage_id"})
 *     }
 * )
 */
class ProductWebpage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="ulid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.ulid_generator")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Product::class, inversedBy="productWebpage", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\OneToOne(targetEntity=Webpage::class, inversedBy="productWebpage", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $webpage;

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

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
