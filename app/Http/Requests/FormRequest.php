<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest AS LaravelFormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class FormRequest extends LaravelFormRequest
{
    /**
     * required rules method
     */
    abstract public function rules();

    /**
     * required authorize method
     */
    abstract public function authorize();

    /**
     * change laravel form validation method json response
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status'=>false,
                'errors'=> $validator->errors()
            ])
        );
    }
}
