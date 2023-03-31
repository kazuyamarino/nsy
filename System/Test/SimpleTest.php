<?php

use PHPUnit\Framework\TestCase;

require_once "ClassWordCount.php";

class SimpleTest extends TestCase
{
    public function testCountWords()
    {
        $Wc = new ClassWordCount();

        $TestSentence = "My name is NSY"; // 4 Kata ..
        $WordCount = $Wc->countWords($TestSentence);

        $this->assertEquals(4, $WordCount);
    }
}
