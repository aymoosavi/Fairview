<?php

namespace Fairview\lib\Exceptions;

class ImageUrlNotProvidedException extends \Exception
{
    public function __construct(string $message = 'Image URL not provided', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}