<?php

namespace App\Http\Requests\Api\News;

use App\Trait\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetNewsRequest extends FormRequest
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
            'page' => 'nullable|integer',
            'per_page' => 'nullable|integer',
            'search' => 'nullable|string',
            'q' => 'nullable|string',
            'category' => 'nullable|array',
            'category.*' => 'integer|exists:categories,id',
            'source' => 'nullable|array',
            'source.*' => 'integer|exists:sources,id',
            'date' => 'nullable|date'
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorResponse('Validation Error',[$validator->errors()],403));
    }
}
