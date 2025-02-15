<?php

namespace App\Trait;

trait ResponseTrait
{
    /**
     * @OA\Schema(
     *     schema="SuccessResponse",
     *     title="SuccessResponse",
     *     description="Success Response",
     *     type="object",
     *     @OA\Property(property="status", type="boolean", example=true),
     *     @OA\Property(property="message", type="string", example="Success message"),
     *     @OA\Property(property="data", type="object", example={"field_name": "value"})
     * )
     */
    public function successResponse($message, $data = null,$status = 200, $pagination = null)
    {
        $response = [
            'status' => true,
            'message' => $message,
            'data' => $data,
        ];

        if ($pagination) {
            $response['pagination'] = $pagination;
        }

        return response()->json($response,  $status);
    }
    /**
     * @OA\Schema(
     *     schema="ErrorResponse",
     *     title="ErrorResponse",
     *     description="Error Response",
     *     type="object",
     *     @OA\Property(property="status", type="boolean", example=false),
     *     @OA\Property(property="message", type="string", example="Error message"),
     *     @OA\Property(property="errors", type="object", example={"field_name": {"Error message"}})
     * )
     */
    public function errorResponse($message, $errors = [], $statusCode = 400)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }
}
