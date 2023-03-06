<?php

namespace Classifier;

use PHPUnit\Framework\TestCase;
use App\Classifier\BayesClassifier;

final class CategoryTest extends TestCase
{
    public function testCalculateEvenProbability()
    {
        $classifier = new BayesClassifier();

        $classifier->learn("numbers", "one two three four");
        $category = $classifier->fetchCategory("numbers");

        $oneProbability = $category->calculateTermProbability("one", count($classifier->dictionary));
        
        $this->assertSame($oneProbability, 0.25);
    }

    public function testCalculateWeightedProbability()
    {
        $classifier = new BayesClassifier();

        $classifier->learn("numbers", "one two two three");
        $category = $classifier->fetchCategory("numbers");

        $oneProbability = $category->calculateTermProbability("one", count($classifier->dictionary));
        $twoProbability = $category->calculateTermProbability("two", count($classifier->dictionary));
        $threeProbability = $category->calculateTermProbability("three", count($classifier->dictionary));
        
        $this->assertSame($oneProbability, 0.2857142857142857);
        $this->assertSame($twoProbability, 0.42857142857142855);
        $this->assertSame($threeProbability, 0.2857142857142857);
    }
}