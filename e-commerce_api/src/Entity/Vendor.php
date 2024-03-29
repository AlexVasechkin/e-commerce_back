<?php

namespace App\Entity;

use App\Domain\Contracts\VendorInterface;
use App\Entity\Contracts\PutVendorInterface;
use App\Repository\VendorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=VendorRepository::class)
 * @ORM\Table(
 *     name="vendor",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="ix_uq_vendor", columns={"name"})
 *     }
 * )
 */
class Vendor
    implements VendorInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api_dict"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_dict"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="vendor")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
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
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setVendor($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getVendor() === $this) {
                $product->setVendor(null);
            }
        }

        return $this;
    }

    public function put(PutVendorInterface $request): self
    {
        return $this
            ->setName($request->getName())
        ;
    }
}
