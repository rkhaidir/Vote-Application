<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function postLogin(Request $request) {
        // dd($request->all());
        $rules = [
            'username' => 'required',
            'password'  => 'required'
        ];

        $messages = [
            'username.required'     => 'Name is required',
            'password.required'     => 'Password is required'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $data = [
            'username'  => $request->input('username'),
            'password'  => $request->input('password'),
        ];

        Auth::attempt($data);
        if (Auth::check()) {
            return redirect('home');
        }
        return redirect();
    }

    public function logout() {
        Auth::logout();
        return redirect('/');
    }
}
