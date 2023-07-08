<?php

namespace App\Http\Requests;

use App\Helpers\ResponseBuilder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProfileRequest extends FormRequest
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
            "name"=>['required','string','max:200'],
            'image'=>['mimes:png,jpg','max:2048'],

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ResponseBuilder::error($validator->errors()->first(),400));
    }
}
