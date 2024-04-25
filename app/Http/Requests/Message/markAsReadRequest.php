<?php

namespace App\Http\Requests\Message;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class markAsReadRequest extends FormRequest
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
            'id' => 'required|numeric|exists:messages,id',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $id = $this -> input('id');
            $sender_id = DB::table('messages') -> where('id',$id) -> value('sender_id') ;
            if ($sender_id == Auth::user() -> id) {
                $validator -> errors() -> add('id', 'You cannot mark your own message as read.');
            }
        });
    }

    /**
     * Handle the failed validation event.
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
