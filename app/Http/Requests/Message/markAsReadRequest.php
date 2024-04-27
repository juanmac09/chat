<?php

namespace App\Http\Requests\Message;

use App\Models\Message;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

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
            $message = Message::find($id);
            $user = Auth::user();
            if ($message -> sender_id == $user -> id) {
                $validator -> errors() -> add('id', 'You cannot mark your own message as read.');
            }
         
            if ($message -> recipient() -> value('recipient_type') == 'group' && !$user -> groups() -> where('groups.id', $id) -> first()) {
               $validator -> errors() ->add('id', 'You cannot mark messages from groups to which you do not belong as read');
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
