<?php

/**
 * Date: 27.04.2016
 * Time: 12:30
 */
class Test1
{
    private $a;
    private $b;

    public function __construct($a, $b)
    {
        $this->a = $a;
        $this->b = $b;
    }

    protected function sum() {
        return $this->a + $this->b;
    }
}