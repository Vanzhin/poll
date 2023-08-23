<?php

namespace App\Response;

use Throwable;

class AppException extends \Exception
{
    public function __construct($message = "", $code = 422, private array $log = [], Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getLog(): array
    {
        return $this->log;
    }
}