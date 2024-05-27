<?php

namespace App\Http\Requests\Exports;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserActivityRequest extends FormRequest
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
        $route = $this-> route() -> uri();

        // $recipient_type_rule = ($route == 'api/report/users/inactive-general' || $route == 'api/report/groups/inactive' || $route == 'api/report/users/active-general') ? '' : 'required|numeric|between:1,2';

        return [
            'amount' => 'numeric|min:1|required|integer',
            'conversion_type' => 'numeric|required|integer|between:1,5'
        ];
    }


    /**
     * Handle the failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['error' => $validator->errors()], 422)
        );
    }
}