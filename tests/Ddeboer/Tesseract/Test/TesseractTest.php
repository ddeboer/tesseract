<?php
namespace Ddeboer\Tesseract\Test;

use Ddeboer\Tesseract\Tesseract;

class TesseractTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSupportedLanguages()
    {
        $tesseract = new Tesseract();
        $this->assertEquals(array('eng', 'nld'), $tesseract->getSupportedLanguages());
    }
}
