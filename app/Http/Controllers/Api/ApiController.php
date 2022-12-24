<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    protected $errorsMessages = [];

    /**
     * API Validation function
     *
     * @param [type] $request
     * @param [type] $rules
     * @param array $messages
     * @return boolean
     */
    public function apiValidation($request, $rules, $messages = [])
    {
        $validation = Validator::make($request->all(), $rules, $messages);
        $this->errorsMessages = $validation->errors();
        return $validation->fails();
    }
}
