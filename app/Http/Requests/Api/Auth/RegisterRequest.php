<?php

namespace App\Http\Requests\Api\Auth;

use App\Trait\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @OA\Schema(
 *      title="RegisterRequest",
 *      description="Register Request",
 *      type="object",
 *      required={"name","email","password"},
 *      @OA\Property(
 *          property="name",
 *          type="string",    
 *          description="Name of the User",
 *          example="John Doe"
 *      ),
 *      @OA\Property(
 *          property="email",
 *          type="string",
 *          description="Email of the User",
 *          example="john.doe@example.com"
 *      ),
 *      @OA\Property(
 *          property="password",
 *          type="string",
 *          description="Password of the User",
 *          example="password"
 *      ),
 *      @OA\Property(
 *         property="password_confirmation",
 *          type="string",
 *          description="Password Confirmation",
 *          example="password"
 *   )
 * 
 * )
 */
class RegisterRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed'
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorResponse('Validation Error',[$validator->errors()],403));
    }
}
