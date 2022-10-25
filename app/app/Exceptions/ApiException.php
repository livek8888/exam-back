<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    public function render()
    {
        return response()->json(
            [
                'message' => $this->getMessage()
            ],
            $this->getCode()
        );
    }
}
