<?php
namespace Ddeboer\Tesseract;

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
        $languages = $this->execute(array('--list-langs'));
        // Shift to remove first line: List of available languages (x):
        \array_shift($languages);
        
        return $languages;
    }
    
    public function getVersion()
    {
        return $this->execute(array('--version'));
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
        $tempFile = tempnam(sys_get_temp_dir(), 'tesseract');
        $this->execute(array($filename, $tempFile));
        
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
    protected function execute(array $parameters)
    {
        $escaped = \array_map(
            function ($parameter) {
                return escapeshellarg($parameter);
            },
            $parameters
        );
        
        \exec(
            sprintf(
                '%s %s 2>&1',
                $this->path,
                implode(' ', $escaped)
            ),
            $output,
            $returnVar
        );

        if ($returnVar !== 0) {
            throw new \RuntimeException('Error occurred: ' . implode(', ', $output));
        }
        
        return $output;
    }
}
