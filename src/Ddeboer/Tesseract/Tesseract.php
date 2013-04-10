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
        $languages = $this->execute('--list-langs');
        // Shift to remove first line: List of available languages (x):
        \array_shift($languages);
        
        return $languages;
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
        \exec(
            sprintf(
                '%s %s 2>&1',
                $this->path,
                escapeshellarg($parameters)
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
