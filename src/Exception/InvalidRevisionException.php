<?php

/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 11/02/18
 * Time: 18:26
 */

namespace App\Exception;


use Exception;
use Throwable;

class InvalidRevisionException extends Exception
{
    public function __construct(
        string $message = "",
        int $code = -1,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

}