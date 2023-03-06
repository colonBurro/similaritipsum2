<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 9)]
    private ?int $documentCount = null;

    #[ORM\Column(length: 9)]
    private ?int $termCount = null;

    #[ORM\Column]
    private array $termFrequencies = [];

    public function __construct(string $name = "")
    {
        $this->name = $name;
        $this->documentCount = 0;
        $this->termCount = 0;
        $this->termFrequencies = [];
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

    public function getDocumentCount(): ?int
    {
        return $this->documentCount;
    }

    public function setDocumentCount(int $documentCount): self
    {
        $this->documentCount = $documentCount;

        return $this;
    }

    public function getTermCount(): ?int
    {
        return $this->termCount;
    }

    public function setTermCount(int $termCount): self
    {
        $this->termCount = $termCount;

        return $this;
    }

    public function getTermFrequencies(): array
    {
        return $this->termFrequencies;
    }

    public function setTermFrequencies(array $termFrequencies): self
    {
        $this->termFrequencies = $termFrequencies;

        return $this;
    }
}
