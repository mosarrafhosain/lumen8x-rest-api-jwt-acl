<?php

namespace App\Http\Controllers;

class BaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        $response = [
            'data' => $result,
            'error' => false,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'error' => true,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
