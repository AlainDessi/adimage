<?php

use AdWeb\AdImage;

class GetResizeTests extends PHPUnit_Framework_TestCase
{
    public function testResizeAndCropJpg()
    {
        $image = new Adweb\AdImage('tests/images/img1.jpg');
        $result = $image->resize('tests/results/test1.jpg', 400, 400, true);
        $this->assertTrue($result);
    }

    public function testResizeHeightPng()
    {
        $image = new Adweb\AdImage('tests/images/img1.png');
        $result = $image->resizeHeight('tests/results/test2.png', 400);
        $this->assertTrue($result);
    }

    public function testResizeWidthGif()
    {
        $image = new Adweb\AdImage('tests/images/img1.gif');
        $result = $image->resizeWidth('tests/results/test3.gif', 400);
        $this->assertTrue($result);
    }
}
