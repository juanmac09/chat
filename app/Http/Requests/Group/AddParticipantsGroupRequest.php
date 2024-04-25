<?php

namespace App\Http\Requests\Group;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddParticipantsGroupRequest extends FormRequest
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
            'id' => 'required|exists:groups,id|numeric|integer',
            'participants' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $user_id = Auth::id();
                    if (in_array($user_id, $value)) {
                        $fail('The authenticated user cannot be on the participant list.');
                    }
                },
            ],
            'participants.*' => 'numeric|integer|exists:users,id',
        ];
    }

    /**
     * Adds custom validation logic after the main validation.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $id = $this->input('id');
            $status = DB::table('groups')->where('id', $id)->value('status');

            if ($status !== 1) {
                $validator->errors()->add('id', 'The group with ID ' . $id . ' does not have active status.');
            }
        });
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
