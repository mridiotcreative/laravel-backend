<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait HttpResponseTraits
{
    protected function errors($message = '', $errors = [], $status = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'errors' => !empty($errors) ?  $errors : json_decode('{}'),
        ], $status);
    }
    protected function success($message = '', $data = [], $status = Response::HTTP_OK)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'result' => !empty($data) ? $data : json_decode('{}'),
        ], $status);
    }

    protected function failure($message = '', $status = Response::HTTP_BAD_REQUEST, $data = [])
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'result' => !empty($data) ? $data : json_decode('{}'),
        ], $status);
    }
}
