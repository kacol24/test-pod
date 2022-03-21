<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username'    => ['required', 'string', 'max:255', 'unique:stores,storename'],
            'name'        => ['required', 'string', 'max:255'],
            'phone'       => ['required', 'numeric', 'unique:users'],
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],
            'description' => ['required'],
            'what_to_do'  => ['required'],
            'terms'       => ['accepted'],
        ];
    }

    public function attributes()
    {
        return [
            'username'    => 'account name',
            'description' => '',
            'what_to_do'  => '',
            'terms'       => 'terms',
        ];
    }
}
