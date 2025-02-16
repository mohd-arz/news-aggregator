<?php

namespace App\Http\Requests\Api\Preferences;

use App\Trait\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
/**
 * @OA\Schema(
 *      title="SetPreferenceRequest",
 *      description="Request body for setting user preferences",
 *      type="object",
 *      required={"preference_name", "value"},
 *      
 *      @OA\Property(
 *          property="preference_name",
 *          type="array",
 *          description="Array of preference names",
 *          @OA\Items(
 *              type="string",
 *              example="source"
 *          )
 *      ),
 *      
 *      @OA\Property(
 *          property="value",
 *          type="object",
 *          description="Object containing preference values grouped by preference name",
 *          @OA\AdditionalProperties(
 *              type="array",
 *              @OA\Items(
 *                  type="integer",
 *                  example=1
 *              )
 *          )
 *      )
 * )
 */
class SetPreferenceRequest extends FormRequest
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
            'preference_name' => 'required|array',
            'preference_name.*' => 'string',
            'value' => 'required|array',
            'value.*' => 'required|array',
            'value.*.*' => 'integer',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorResponse('Validation Error',[$validator->errors()],403));
    }
}
