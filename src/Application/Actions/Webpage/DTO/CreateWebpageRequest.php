<?php

namespace App\Application\Actions\Webpage\DTO;

use App\Entity\Contracts\PutWebpageInterface;
use App\Entity\Webpage;

class CreateWebpageRequest
    implements PutWebpageInterface
{
    private string $name;

    private string $alias;

    private ?string $pagetitle = null;

    private ?string $headline = null;

    private ?string $description = null;

    private ?string $content = null;

    private ?Webpage $parent = null;

    private bool $isActive = false;

    public function __construct(string $name, string $alias)
    {
        $this->name = $name;
        $this->alias = $alias;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getPagetitle(): ?string
    {
        return $this->pagetitle;
    }

    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getParent(): ?Webpage
    {
        return $this->parent;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;
        return $this;
    }

    public function setPagetitle(?string $pagetitle): self
    {
        $this->pagetitle = $pagetitle;
        return $this;
    }

    public function setHeadline(?string $headline): self
    {
        $this->headline = $headline;
        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function setParent(?Webpage $parent): self
    {
        $this->parent = $parent;
        return $this;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }
}
