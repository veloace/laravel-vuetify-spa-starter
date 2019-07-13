<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserSelfServicePasswordChangeRequest;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAccountController extends Controller
{
    //

    public function changePassword(UserSelfServicePasswordChangeRequest $request)
    {

        User::where('id',Auth::id())
            ->update(['password'=>Hash::make($request['new_password'])]);

        return response(['success_message'=>'Success! Your Password has been changed.'],200);
    }
}
