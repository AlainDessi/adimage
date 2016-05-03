<?php

use AdWeb\AdImage;

class SizeTest extends PHPUnit_Framework_TestCase
{
    public function testGetWidth()
    {
        $image = new Adweb\AdImage('tests/images/img1.jpg');
        $this->assertEquals(1024, $image->getWidth());
    }

    public function testGetHeight()
    {
        $image = new Adweb\AdImage('tests/images/img1.jpg');
        $this->assertEquals(768, $image->getHeight());
    }

    public function testRatio()
    {
        $image = new Adweb\AdImage('tests/images/img1.jpg');
        $this->assertEquals(1.3333333333333, $image->getRatio());
    }
}
