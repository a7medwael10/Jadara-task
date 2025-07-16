<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ApiResponse
{
    protected function successResponse($data = null, $message = null, $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors'=> null,
            'code' => $code
        ], $code);
    }

    protected function errorResponse($errors = null,$message = null, $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
            'code' => $code
        ], $code);
    }


}
