<?php
namespace Ddeboer\Tesseract\Tests;

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
    
    public function testRecognizePng()
    {
        $tesseract = new Tesseract();
        $text = $tesseract->recognize(__DIR__.'/Fixtures/test-nld.png', array('nld'));
        $this->assertEquals("Het eerste is slechts mogelijk indien ILO-verdrag 96 zou worden opgezegd. Aangezien dit\npas in 2001 mogelijk is, wordt onderzocht in hoeverre in de tussenliggende periode tot\nverdere deregulering kan worden overgegaan, zonder dat uiteraard strijd met de\nverdragsverplichtingen ontstaat. Hierover is (opnieuw) contact met het Bureau van de ILO\n\ngelegd.\n\nIn dit verband is verder van belang dat op de 81e zitting van de Internationale\nArbeidsconferentie een algemene discussie over het onderwerp \"de rol van particuliere\nbureaus bij het functioneren van de arbeidsmarkt\" is gevoerd. Zowel de mi van\narbeidsbureaus als die van uitzendbureaus was hierbij aan de orde.\n\n", $text);
    }
}

