<?php
namespace Ddeboer\Tesseract\Exception;

class CommandException extends \RuntimeException
{
    const ERROR_IMAGE_TYPE = 'Unsupported image type.';
    const ERROR_LANGUAGE = 'Failed loading language';
    
    public function __construct($command, array $output)
    {
        parent::__construct(sprintf('Command %s produced error: %s', $command, \implode("\n", $output)));
    }
    
    public static function factory($command, array $output)
    {
        $string = implode("\n", $output);
        
        if (false !== stripos($string, self::ERROR_LANGUAGE)) {
            return new UnsupportedLanguageException($command, $output);
        } elseif (false !== stripos($string, self::ERROR_IMAGE_TYPE)) {
            return new UnsupportedImageTypeException($command, $output);
        } else {
            return new self($command, $output);
        }
    }
}
