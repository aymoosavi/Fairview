<?php

namespace Fairview\lib\Exceptions;

class RSSFeedURLNotProvidedException extends \Exception
{
    public function __construct(string $message = 'RSS feed URL not provided', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}