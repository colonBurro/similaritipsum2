<?php

namespace Classifier;

use PHPUnit\Framework\TestCase;
use App\Classifier\Tokenizer;

final class TokenizerTest extends TestCase
{
    public function testTokenizer()
    {
        $tokenizer = new Tokenizer();
        $testText = "This;is+a,/TokEnizEr.TeSt:With....,-the repeaTIng WORD:tokenizer-tokenizer?and??!tokenizer,";

        $tokens = $tokenizer->tokenize($testText);

        $this->assertSame(["this", "is", "a", "tokenizer", "test", "with", "the", "repeating", "word", "tokenizer", "tokenizer", "and", "tokenizer"], $tokens);
    }

    public function testCalculateTermFrequencies()
    {
        $tokenizer = new Tokenizer();
        $testTokens = ["this", "is", "a", "tokenizer", "test", "with", "the", "repeating", "word", "tokenizer", "tokenizer", "and", "tokenizer"];

        $termFrequencyTable = $tokenizer->calculateTermFrequencies($testTokens);

        $this->assertSame(["this" => 1, "is" => 1, "a" => 1, "tokenizer"=> 4, "test" => 1, "with" => 1, "the" => 1, "repeating" => 1, "word" => 1, "and" => 1], $termFrequencyTable);
    }
}