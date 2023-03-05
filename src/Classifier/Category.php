<?php

namespace App\Classifier;

class Category
{
    private string $name;

    private int $documentCount;

    private int $termCount;

    private array $termFrequencies;

    public function __construct(string $name = "")
    {
        $this->name = $name;
        $this->documentCount = 0;
        $this->termCount = 0;
        $this->termFrequencies = [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Category
    {
        $this->name = $name;

        return $this;
    }

    public function getDocumentCount(): int
    {
        return $this -> documentCount;
    }

    public function setDocumentCount(int $documentCount): Category
    {
        $this->documentCount = $documentCount;

        return $this;
    }

    public function getTermCount(): int
    {
        return $this->termCount;
    }

    public function setTermCount(int $termCount) :Category
    {
        $this->termCount = $termCount;

        return $this;
    }

    public function getTermFrequencies(): array
    {
        return $this->termFrequencies;
    }

    public function setTermFrequencies(array $termFrequencies): Category
    {
        $this->termFrequencies = $termFrequencies;

        return $this;
    }
}