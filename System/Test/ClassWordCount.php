<?php
class ClassWordCount
{
    public function countWords($sentence)
    {
        return count(explode(" ", $sentence));
    }
}
