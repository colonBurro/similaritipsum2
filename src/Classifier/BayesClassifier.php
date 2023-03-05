<?php

namespace App\Classifier;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Classifier\Tokenizer;
use App\Classifier\Category;

class BayesClassifier
{
    public Tokenizer $tokenizer;

    public ArrayCollection $categories;

    public int $totalDocuments;
    
    public array $dictionary;

    public function __construct()
    {
        $this -> tokenizer = new Tokenizer();
        $this -> categories = new ArrayCollection();
        $this -> totalDocuments = 0;
        $this -> dictionary = [];
    }

    public function checkAndSetCategory($categoryName)
    {
        $category = $this->fetchCategory($categoryName);
        if (!isset($category)){
            $category = new Category($categoryName);
            $this->addCategory($category);
        }

        return $category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function fetchCategory(string $categoryName): ?Category
    {
        foreach($this->categories as $category){
            if($category->getName() == $categoryName) return $category;
        }

        return null;
    }

    public function updateDictionary(String $term): void
    {
        if (!in_array($term, $this->dictionary)) {
            $this->dictionary[] = $term;
        }
    }

    public function learn(string $categoryName, string $document): bool
    {
        $tokens = $this->tokenizer->tokenize($document);
        if(empty($tokens)) return false;

        $category = $this->checkAndSetCategory($categoryName);

        $documentsCount = $category->getDocumentCount();
        $documentsCount++;
        $category->setDocumentCount($documentsCount);

        $this->totalDocuments++;
        $termFrequencies = $this->tokenizer->calculateTermFrequencies($tokens);

        foreach ($termFrequencies as $term => $frequency) {
            $this->updateDictionary($term);
            $category->updateTermFrequency($term, $frequency);
        }

        return true;
    }
}
