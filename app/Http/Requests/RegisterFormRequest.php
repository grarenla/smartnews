<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class RegisterFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'passwordConfirm' => 'required|same:password'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'name.string' => 'Name is not string.',
            'email.required' => 'Email is required',
            'email.email' => 'Email is malformed.',
            'email.unique' => 'Email has been duplicated.',
            'source.required' => 'Source is required',
            'password.required' => 'Password is required',
            'password.string' => 'Password is not string.',
            'password.min' => 'Password must be greater than 6 characters.',
            'passwordConfirm.required' => 'Password confirm is required',
            'passwordConfirm.same' => 'Password confirm must be the same as password.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(
            [
                'errors' => $errors,
                'status_code' => 422,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
