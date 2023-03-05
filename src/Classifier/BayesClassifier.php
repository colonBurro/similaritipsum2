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

    public int $dictionarySize;
    
    public array $dictionary;

    public function __construct()
    {
        $this -> tokenizer = new Tokenizer();
        $this -> categories = new ArrayCollection();
        $this -> dictionary = [];
        $this -> dictionarySize = 0;
        $this -> totalDocuments = 0;
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
}
