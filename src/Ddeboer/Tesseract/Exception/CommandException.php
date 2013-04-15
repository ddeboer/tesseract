<?php
namespace Ddeboer\Tesseract\Exception;

use Symfony\Component\Process\Process;

class CommandException extends \RuntimeException
{
    const ERROR_IMAGE_TYPE = 'Unsupported image type.';
    const ERROR_LANGUAGE = 'Failed loading language';
    
    public function __construct(Process $process)
    {
        parent::__construct(
            sprintf(
                'Command %s produced error: %s', 
                $process->getCommandLine(), 
                $process->getErrorOutput()
            )
        );
    }
    
    public static function factory(Process $process)
    {
        // Combine STDOUT and STDERR output, because Tesseract is rather messy
        // about separating the two correctly. See, e.g.,
        // https://code.google.com/p/tesseract-ocr/issues/detail?id=813,
        $error = $process->getOutput() . "\n" . $process->getErrorOutput();

        if (false !== stripos($error, self::ERROR_LANGUAGE)) {
            return new UnsupportedLanguageException($process);
        } elseif (false !== stripos($error, self::ERROR_IMAGE_TYPE)) {
            return new UnsupportedImageTypeException($process);
        } else {
            return new self($process);
        }
    }
}
