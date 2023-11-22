<?php

namespace App\Entity;

use App\Repository\FilterStateRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

/**
 * @ORM\Entity(repositoryClass=FilterStateRepository::class)
 * @ORM\Table(
 *     indexes={
 *         @ORM\Index(name="ix_filter_state__name", columns={"name"})
 *     }
 * )
 */
class FilterState
{
    /**
     * @ORM\Id
     * @ORM\Column(type="ulid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.ulid_generator")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @ORM\Column(type="json")
     */
    private $state = [];

    public function getId(): ?Ulid
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

    public function getState(): ?array
    {
        return $this->state;
    }

    public function setState(array $state): self
    {
        $this->state = $state;

        return $this;
    }
}
