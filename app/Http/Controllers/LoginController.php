<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use PDOException;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController
{
    public function getLogin()
    {
        session_start();
        if (isset($_SESSION['user_id'])) {
            return redirect()->route('home');
        } else {
            return view('login');
        }
    }

    public static function attemptLogin(Request $request)
    {
        $request = $request->only('email', 'password');
        try {
            if (!User::checkIfExistsEmail($request['email'])) {
                return redirect('login')->with('email', 'Email no registrado');
            }
            if (self::validateCredentials($request)) {
                session_start();
                $_SESSION['user_id'] = User::getUser($request['email']);
                return redirect()->route('home');
                //return redirect()->route('home')->with('user_id', User::getUser($request['email']));
            } else {
                return redirect('login')->with('password', 'Contraseña incorrecta');
            }
        } catch (PDOException) {
            return redirect('login')->with('error', 'Error al conectar con el servidor');
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