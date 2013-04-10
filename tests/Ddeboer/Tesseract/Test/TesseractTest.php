<?php
namespace Ddeboer\Tesseract\Test;

use Ddeboer\Tesseract\Tesseract;

class TesseractTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSupportedLanguages()
    {
        $tesseract = new Tesseract();
        $this->assertContains('eng', $tesseract->getSupportedLanguages());
    }
    
    public function testGetVersion()
    {
        $tesseract = new Tesseract();
        $version = $tesseract->getVersion();
        $this->assertContains('tesseract 3.', $version[0]);
    }

}
