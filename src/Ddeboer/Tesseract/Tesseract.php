<?php
namespace Ddeboer\Tesseract;

use Ddeboer\Tesseract\Exception\CommandException;

class Tesseract
{
    protected $path;
    
    public function __construct($path = 'tesseract')
    {
        $this->path = $path;
    }
    
    /**
     * Get supported languages (ISO 632-2 language codes)
     * 
     * @return array
     */
    public function getSupportedLanguages()
    {
        $languages = $this->execute('--list-langs');
        // Shift to remove first line: List of available languages (x):
        \array_shift($languages);
        
        return $languages;
    }
    
    public function getVersion()
    {
        return $this->execute('--version');
    }
    
    /**
     * Perform OCR on an image file
     * 
     * @param string $filename
     *
     * @return string Text recognized from the image
     */
    public function recognize($filename, array $languages = null)
    {
        if (null !== $languages) {
            $languages = implode('+', $languages);
        }
        
        $tempFile = tempnam(sys_get_temp_dir(), 'tesseract');
        $this->execute(\escapeshellarg($filename) . ' ' . $tempFile . ' -l ' . $languages);
        
        return \file_get_contents($tempFile . '.txt');
    }
    
    /**
     * Execute command and return output
     * 
     * @param string $parameters
     * @return array
     *
     * @throws \RuntimeException
     */
    protected function execute($parameters)
    {
        $command = sprintf(
            '%s %s 2>&1',
            $this->path,
            $parameters
        );
        
        \exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw CommandException::factory($command, $output);
        }
        
        return $output;
    }
}
