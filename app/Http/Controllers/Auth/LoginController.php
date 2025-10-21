<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    // public function credentials(Request $request){
    //     return ['email'=>$request->email,'password'=>$request->password,'status'=>'active','role'=>'0'];
    // }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function changePassword(){
        return view('admin.change_password');
    }

}



