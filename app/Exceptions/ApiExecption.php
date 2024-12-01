<?php

namespace App\Exceptions;

use Exception;

class ApiExeption extends Exception
{
    public function render($request, Exception $exception)
    {
        return response()->json([
            'error' => [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ]
        ], $exception->getCode());
    }
}
