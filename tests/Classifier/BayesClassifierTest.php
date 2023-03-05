<?php

namespace Classifier;

use PHPUnit\Framework\TestCase;
use App\Classifier\BayesClassifier;

final class BayesClassifierTest extends TestCase
{
    public function testDictionaryUpdate()
    {
        $classifier = new BayesClassifier();

        $classifier->updateDictionary("one");
        $classifier->updateDictionary("two");
        $classifier->updateDictionary("three");
        $classifier->updateDictionary("three");
        $classifier->updateDictionary("five");


        $this->assertSame(["one", "two", "three", "five"], $classifier->dictionary);
    }

    public function testLearnSingle()
    {
        $classifier = new BayesClassifier();

        $classifier->learn("lorem ipsum", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem ipsum, Lorem ipsum dolor sit amet");
        $category = $classifier->fetchCategory("lorem ipsum");

        
        $this->assertSame(["lorem","ipsum", "dolor", "sit", "amet", "consectetur", "adipiscing" ,"elit"], $classifier->dictionary);
        $this->assertSame(1, $category->getDocumentCount());
        $this->assertSame(15, $category->getTermCount());
        $this->assertSame(["lorem" => 3,"ipsum" => 3, "dolor" => 2, "sit" => 2, "amet" => 2, "consectetur" => 1, "adipiscing" => 1, "elit" => 1], $category->getTermFrequencies());
    }

    public function testLearnMultiple()
    {
        $classifier = new BayesClassifier();

        $classifier->learn("lorem ipsum", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem ipsum, Lorem ipsum dolor sit amet");
        $classifier->learn("lorem ipsum", "Lorem ipsum ipsum, amet amet, dolor sit");
        $classifier->learn("not lorem ipsum", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem ipsum, Lorem ipsum dolor sit amet. Bacon bacon chuck");

        $this->assertSame(["lorem","ipsum", "dolor", "sit", "amet", "consectetur", "adipiscing" ,"elit", "bacon", "chuck"], $classifier->dictionary);

        $categoryLoremIpsum = $classifier->fetchCategory("lorem ipsum");
        $this->assertSame(2, $categoryLoremIpsum->getDocumentCount());
        $this->assertSame(22, $categoryLoremIpsum->getTermCount());
        $this->assertSame(["lorem" => 4,"ipsum" => 5, "dolor" => 3, "sit" => 3, "amet" => 4, "consectetur" => 1, "adipiscing" => 1, "elit" => 1], $categoryLoremIpsum->getTermFrequencies());

        $categoryNotLoremIpsum = $classifier->fetchCategory("not lorem ipsum");
        $this->assertSame(1, $categoryNotLoremIpsum->getDocumentCount());
        $this->assertSame(18, $categoryNotLoremIpsum->getTermCount());
        $this->assertSame(["lorem" => 3,"ipsum" => 3, "dolor" => 2, "sit" => 2, "amet" => 2, "consectetur" => 1, "adipiscing" => 1, "elit" => 1, "bacon" => 2, "chuck" => 1], $categoryNotLoremIpsum->getTermFrequencies());
    }

    public function testCalculateCategoryProbabilities()
    {
        $classifier = new BayesClassifier();

        $classifier->learn("numbers", "five six seven eight");
        $classifier->learn("words", "one two three four");
        $category = $classifier->fetchCategory("numbers");

        $oneProbability = $classifier->calculateCategoryProbabilities("one two five six");
        
        $this->assertSame($oneProbability, ["numbers" => -9.246479418592056, "words" => -9.246479418592056]);
    }
}