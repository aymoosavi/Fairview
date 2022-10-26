<?php

namespace Fairview\lib\Exceptions;

class RSSFeedNotAvailableException extends \Exception
{
    public function __construct(string $message = 'RSS feed not available', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}