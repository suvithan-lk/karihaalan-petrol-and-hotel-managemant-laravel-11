<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin(){
        $year = Carbon::now()->year;
        return view('login',['year' => $year]);
    }

    public function login(Request $request){
        $valid = new Validator(
            $request->all(),
            [
                'phone' =>'required|min:10|max:12',
                'password' => 'required|min:8'
            ]
        );
        
        if (!$valid) {
            return back()->withInput()->withErrors($valid);
        }

        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password])) {
            return redirect('/dashboard');
        }

        return back()->withInput()->withErrors(['email' => 'Invalid email or password']);
    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }
}
