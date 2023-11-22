<?php

namespace App\Entity;

use App\Repository\ProductPropertyValueRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

/**
 * @ORM\Entity(repositoryClass=ProductPropertyValueRepository::class)
 * @ORM\Table(
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="ix_uq_property_value",
 *             columns={"product_id", "property_id"}
 *         )
 *     },
 *     indexes={
 *         @ORM\Index(name="ix_property", columns={"property_id"})
 *     }
 * )
 */
class ProductPropertyValue
{
    /**
     * @ORM\Id
     * @ORM\Column(type="ulid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.ulid_generator")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="productPropertyValues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=CategoryProperty::class, inversedBy="productPropertyValues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $property;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stringValue;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $intValue;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $floatValue;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datetimeValue;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $boolValue;

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

    public function getProperty(): ?CategoryProperty
    {
        return $this->property;
    }

    public function setProperty(?CategoryProperty $property): self
    {
        $this->property = $property;

        return $this;
    }

    public function getStringValue(): ?string
    {
        return $this->stringValue;
    }

    public function setStringValue(?string $stringValue): self
    {
        $this->stringValue = $stringValue;

        return $this;
    }

    public function getIntValue(): ?int
    {
        return $this->intValue;
    }

    public function setIntValue(?int $intValue): self
    {
        $this->intValue = $intValue;

        return $this;
    }

    public function getFloatValue(): ?float
    {
        return $this->floatValue;
    }

    public function setFloatValue(?float $floatValue): self
    {
        $this->floatValue = $floatValue;

        return $this;
    }

    public function getDatetimeValue(): ?\DateTimeInterface
    {
        return $this->datetimeValue;
    }

    public function setDatetimeValue(?\DateTimeInterface $datetimeValue): self
    {
        $this->datetimeValue = $datetimeValue;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        $type = $this->getProperty()->getType();

        if ($type === 'string') {
            return $this->getStringValue();
        } elseif ($type === 'int') {
            return $this->getIntValue();
        } elseif ($type === 'float') {
            return $this->getFloatValue();
        } elseif ($type === 'datetime') {
            return $this->getDatetimeValue()->format('D, d M Y H:i:s \G\M\T');
        } elseif ($type === 'bool') {
            return $this->getBoolValue();
        } else {
            return null;
        }
    }

    public function setValue(CategoryProperty $property, $value): self
    {
        if ($property->getType() === 'string') {
            $this->setStringValue($value);
        } elseif ($property->getType() === 'int') {
            $this->setIntValue($value);
        } elseif ($property->getType() === 'float') {
            $this->setFloatValue($value);
        } elseif ($property->getType() === 'datetime') {
            $this->setDatetimeValue(new DateTime($value));
        } elseif ($property->getType() === 'bool') {
            $this->setBoolValue($value);
        }

        return $this;
    }

    public function getBoolValue(): ?bool
    {
        return $this->boolValue;
    }

    public function setBoolValue(?bool $boolValue): self
    {
        $this->boolValue = $boolValue;
        return $this;
    }
}
