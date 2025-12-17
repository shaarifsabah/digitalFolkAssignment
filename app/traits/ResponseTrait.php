<?php

namespace App\traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{

    /**
     * @param bool $success
     * @param $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public function sendResponse(bool $success = true, $data = null, string $message = "", int $statusCode = 200): JsonResponse
    {
        if ($success) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data'    => $data,
            ], $statusCode);
        }
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $statusCode);
    }
}
