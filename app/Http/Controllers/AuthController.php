<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login() {
        if(Auth::check())
            return Redirect::route('news.list');

        return view('login', ['msg' => '']);
    }

    
    public function doLogin() {

        if(isset($_POST["username"]) && isset($_POST["password"])) {

            $username = self::makeValidInput($_POST["username"]);
            $password = self::makeValidInput($_POST["password"]);

            if(Auth::attempt(['email' => $username, 'password' => $password], true)) {
                return Redirect::route('news.list');
            }

            return view('login', ['msg' => 'نام کاربری یا رمز عبور را اشتباه وارد کرده اید']);
        }

        return view('login', ['msg' => '']);
    }

    public function logout() {
        Auth::logout();
        Session::flush();
        return Redirect::route('login');
    }

    
    public function changePass() {
        return view('changePass');
    }

    public function doChangePass() {

        if(isset($_POST["newPass"]) && isset($_POST["oldPass"]) && isset($_POST["confirmPass"])) {

            $newPass = self::makeValidInput($_POST["newPass"]);
            $oldPass = self::makeValidInput($_POST["oldPass"]);
            $confirmPass = self::makeValidInput($_POST["confirmPass"]);

            if($newPass != $confirmPass) {
                echo "nok1";
                return;
            }

            $user = Auth::user();

            if(!Hash::check($oldPass, $user->password)) {
                echo "nok2";
                return;
            }

            $user->password = Hash::make($newPass);
            $user->save();

            echo "ok";
        }

    }

}
