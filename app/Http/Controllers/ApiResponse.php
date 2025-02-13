<?php
namespace App\Http\Controllers;

class ApiResponse
{
    public static function success($data = null, $message = "Success", $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    public static function error($message = "Error", $status = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => null,
        ], $status);
    }
}
