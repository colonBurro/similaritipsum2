<?php

namespace App\Classifier;

class Tokenizer
{
    public function tokenize(string $text): array 
    {
        return preg_split('/\W/', strtolower($text), 0, PREG_SPLIT_NO_EMPTY); 
    }
}
