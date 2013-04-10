<?php
namespace Ddeboer\Tesseract\Exception;

class CommandException extends \RuntimeException
{
    public function __construct($command, array $output)
    {
        parent::__construct(sprintf('Command %s produced error: %s', $command, \implode("\n", $output)));
    }
    
    public static function factory($command, array $output)
    {
        switch (\end($output)) {
            case 'Unsupported image type.':
                return new UnsupportedImageTypeException($command, $output);
            default:
                return new CommandException($command, $output);
        }
    }
}
