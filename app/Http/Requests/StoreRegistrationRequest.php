<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'username' => 'required|string|max:255',
            'phonenumber' => 'required|string|max:255',
        ];
    }
}
