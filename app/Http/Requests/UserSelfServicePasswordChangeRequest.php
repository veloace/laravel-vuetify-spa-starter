<?php

namespace App\Http\Requests;

use App\Rules\CheckCorrectPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserSelfServicePasswordChangeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();//user must be logged in
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'old_password'=>new CheckCorrectPassword(),
            'new_password'=>'required|confirmed'
        ];
    }
}
