<?php

use AdWeb\AdImage;

class GetTypeTest extends PHPUnit_Framework_TestCase
{
    public function testGetTypeJpg()
    {
        $image = new Adweb\AdImage('tests/images/img1.jpg');
        $this->assertEquals('jpg', $image->getType());
    }

    public function testGetTypeBmp()
    {
        $image = new Adweb\AdImage('tests/images/img1.bmp');
        $this->assertEquals('bmp', $image->getType());
    }

    public function testGetTypeGif()
    {
        $image = new Adweb\AdImage('tests/images/img1.gif');
        $this->assertEquals('gif', $image->getType());
    }

    public function testGetTypePng()
    {
        $image = new Adweb\AdImage('tests/images/img1.png');
        $this->assertEquals('png', $image->getType());
    }
}
