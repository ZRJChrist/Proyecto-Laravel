<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\SessionManager;
use App\Models\Validator;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController
{
    private $existsUser;

    public function __construct()
    {
        $this->existsUser = SessionManager::read('user_id') !== false ?  true : false;
    }
    public function getSignIn()
    {
        if ($this->existsUser) {
            return redirect()->route('listTask');
        } else {
            return view('content.login');
        }
    }
    public function getSigUp()
    {
        if ($this->existsUser) {
            return redirect()->route('listTask');
        } else {
            return view('content.register');
        }
    }

    public  function attemptSignIn(Request $request)
    {
        $request = $request->only('email', 'password');

        if (!User::checkIfExistsEmail($request['email'])) {
            return redirect()->route('login')->with('email', 'Email no registrado');
        }
        if (self::validateCredentials($request)) {
            SessionManager::write('user_id', User::getUser($request['email']));
            return redirect()->route('listTask');
        } else {
            return redirect('login')->with('password', 'Contraseña incorrecta');
        }
    }
    public function attentSignUp(Request $request)
    {
        $validator = new Validator();
        $data = $request->only('name', 'email', 'password', 'password_confirmation');
        $validator->validateName($data['name']);
        $validator->validateEmail($data['email']);
        $validator->validatePassword($data['password']);
        $validator->validatePasswordConfirmation($data['password'], $data['password_confirmation'], 'password_confirmation');

        if (!$validator->hasErrors()) {
            $inputsOld = array_map(function ($campo) {
                Validator::sanitizeInput($campo);
                return $campo;
            }, ['name' => $data['name'], 'email' => $data['email']]);
            return redirect()->route('register')->with(['error' => $validator->getErrorHandler(), 'old' => $inputsOld]);
        } else {
            $result = User::createUser($data);

            if ($result['result']) {
                return redirect()->route('login');
            } else {
                return redirect()->route('register')->with('errorDB', $result['message']);
            }
        }
    }
    private static function validateCredentials($credentials)
    {
        return Hash::check($credentials['password'], User::credentials($credentials));
    }

    public static function logOutSession()
    {
        SessionManager::startSession();
        unset($_SESSION['user_id']);
        return redirect()->route('login');
    }
}
