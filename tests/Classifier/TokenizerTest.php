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
}