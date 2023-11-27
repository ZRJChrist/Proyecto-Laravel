<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\SessionManager;
use PDOException;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController
{
    public function getLogin()
    {
        if (SessionManager::read('user_id')) {
            return redirect()->route('home');
        } else {
            return view('content.login');
        }
    }

    public static function attemptLogin(Request $request)
    {
        $request = $request->only('email', 'password');

        if (!User::checkIfExistsEmail($request['email'])) {
            return redirect('login')->with('email', 'Email no registrado');
        }
        if (self::validateCredentials($request)) {
            SessionManager::write('user_id', User::getUser($request['email']));
            return redirect()->route('home');
        } else {
            return redirect('login')->with('password', 'Contraseña incorrecta');
        }
    }

    private static function validateCredentials($credentials)
    {
        return Hash::check($credentials['password'], User::credentials($credentials));
    }

    public static function logOutSession()
    {
        session_start();
        unset($_SESSION['user_id']);
        return redirect()->route('login');
    }
}
