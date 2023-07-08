<?php

namespace App\Http\Requests;

use App\Helpers\ResponseBuilder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ItemAddRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', Rule::unique('items', 'name')->where(
                fn ($q) =>
                $q->where('category_id', $this->category_id)
                    ->where('user_id', $this->user()->id)
            ), 'max:255']

        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ResponseBuilder::error($validator->errors()->first(), 400));
    }
}
