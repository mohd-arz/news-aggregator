<?php

namespace App\Http\Requests\Api\Auth;

use App\Trait\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @OA\Schema(
 *      title="Reset Password Request",
 *      description="Schema for Reset Password Request",
 *      type="object",
 *      required={"email", "password", "token"},
 *      @OA\Property(
 *          property="email",
 *          type="string",
 *          format="email",
 *          description="Email of the User",
 *          example="example@gmail.com"
 *      ),
 *      @OA\Property(
 *          property="password",
 *          type="string",
 *          description="New password of the User",
 *          example="password123"
 *      ),
 *      @OA\Property(
 *          property="password_confirmation",
 *          type="string",
 *          description="Password Confirmation",
 *          example="password123"
 *      ),
 *      @OA\Property(
 *          property="token",
 *          type="string",
 *          description="Password reset token",
 *          example="abc123def456"
 *      )
 * )
 */

class ResetRequest extends FormRequest
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
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorResponse('Validation Error',[$validator->errors()],403));
    }
}
