<?php

namespace App\Helpers;

class Response
{
    public static function success($data = [], $message = 'Success', $status = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public static function error($errors = [], $message = 'Error', $status = 422)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => is_array($errors) ? array_values($errors) : [$errors],
        ], $status);
    }
}
