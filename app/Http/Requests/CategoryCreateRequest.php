<?php

namespace App\Http\Requests;

use App\Helpers\ResponseBuilder;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryCreateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'name' => ['required', 'max:255', 'string', Rule::unique('categories', 'name')->where(
                fn ($q) =>
                $q->where('user_id', $this->user()->id)
            )],

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ResponseBuilder::error($validator->errors()->first(), 400));
    }
}
