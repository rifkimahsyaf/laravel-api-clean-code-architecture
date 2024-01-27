<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    public $message;
    public $code;

    public function __construct($message, $code = 400)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function render()
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}
