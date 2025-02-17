<?php

namespace App\Http\Requests\Api\Auth;

use App\Trait\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @OA\Schema(
 *      title="Reset Mail Request",
 *      description="Schema for Reset Password Email Request",
 *      type="object",
 *      required={"email"},
 *      @OA\Property(
 *          property="email",
 *          type="string",
 *          format="email",
 *          description="User's email for password reset",
 *          example="example@gmail.com"
 *      )
 * )
 */

class ResetMailRequest extends FormRequest
{
    use ResponseTrait;
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
            'email' => 'required|email',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorResponse('Validation Error',[$validator->errors()],403));
    }
}
