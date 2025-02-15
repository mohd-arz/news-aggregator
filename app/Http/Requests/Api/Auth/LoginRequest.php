<?php

namespace App\Http\Requests\Api\Auth;

use App\Trait\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @OA\Schema(
 *      title="LoginRequest",
 *      description="Login Request",
 *      type="object",
 *      required={"email","password"},
 *      @OA\Property(
 *          property="email",
 *          type="string",
 *          description="Email of the User",
 *          example="john@example.com"
 *     ),
 *     @OA\Property(
 *         property="password",
 *        type="string",
 *        description="Password of the User",
 *       example="password"
 *    )
 * )
 */
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required',
            'password' => 'required|min:8'
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorResponse('Validation Error',[$validator->errors()],403));
    }
}
