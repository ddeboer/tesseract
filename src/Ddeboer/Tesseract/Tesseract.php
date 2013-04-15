<?php
namespace Ddeboer\Tesseract;

use Ddeboer\Tesseract\Exception\CommandException;
use Symfony\Component\Process\ProcessBuilder;

/**
 * A wrapper around the Tesseract CLI tool
 * 
 * @author David de Boer <david@ddeboer.nl>
 */
class Tesseract
{
    const PAGE_SEG_MODE_OSD = 0;
    const PAGE_SEG_MODE_AUTOMATIC_OSD = 1;
    const PAGE_SEG_MODE_AUTOMATIC = 2;
    const PAGE_SEG_MODE_AUTOMATIC_OCR = 3;
    const PAGE_SEG_MODE_SINGLE_COLUMN = 4;
    const PAGE_SEG_MODE_SINGLE_BLOCK_VERTICAL = 5;
    const PAGE_SEG_MODE_SINGLE_BLOCK = 6;
    const PAGE_SEG_MODE_SINGLE_LINE = 7;
    const PAGE_SEG_MODE_SINGLE_WORD = 8;
    const PAGE_SEG_MODE_SINGLE_WORD_CIRCLE = 9;
    const PAGE_SEG_MODE_SINGLE_CHARACTER = 10;

    /**
     * Path to tesseract binary
     * 
     * @var string
     */
    protected $path;
    
    
    /**
     * Constructor
     * 
     * @param string $path Path to tesseract binary (optional)
     */
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
        $languages = \explode(\PHP_EOL, $this->execute(array('--list-langs')));
        
        // Shift to remove first line: 'List of available languages (x)':
        \array_shift($languages);
        
        return $languages;
    }
    
    /**
     * Get version information
     * 
     * @return array
     */
    public function getVersion()
    {
        return $this->execute(array('--version'));
    }
    
    /**
     * Perform OCR on an image file
     * 
     * @param string $filename
     * @param array  $languages   An array of language codes
     * @param int    $pageSegMode Page segmentation mode
     *
     * @return string Text recognized from the image
     */
    public function recognize($filename, array $languages = null, $pageSegMode = self::PAGE_SEG_MODE_AUTOMATIC_OCR)
    {
        if ($pageSegMode < 0 || $pageSegMode > 10) {
            throw new \InvalidArgumentException(
                'Page seg mode must be between 0 and 10'
            );
        }
        
        $tempFile = tempnam(sys_get_temp_dir(), 'tesseract');
        
        $arguments = array(
            $filename,
            $tempFile,
            '-psm',
            $pageSegMode
        );
        
        if (null !== $languages) {
            $arguments[] = '-l';
            $arguments[] = implode('+', $languages);
        }
        
        $this->execute($arguments);
                
        return trim(\file_get_contents($tempFile . '.txt'));
    }
    
    /**
     * Execute command and return output
     * 
     * @param string $parameters
     * @return array
     *
     * @throws \RuntimeException
     */
    protected function execute(array $arguments)
    {
        \array_unshift($arguments, $this->path);
        
        $builder = ProcessBuilder::create($arguments);
        $process = $builder->getProcess();
        $process->run();
        
        if (!$process->isSuccessful()) {
            throw CommandException::factory($process);
        }
        
        // E.g. tesseract --version returns output as error output
        return $process->getOutput() ? $process->getOutput() : $process->getErrorOutput();
    }
}
