<?php

namespace App\Http\Controllers;

use App\Models\Validator;
use App\Models\User;
use App\Models\SessionManager;
use Illuminate\Http\Request;

class RegisterController
{
    public function getLogin()
    {

        if (SessionManager::read('user_id')) {
            return redirect()->route('home');
        } else {
            return view('content.register');
        }
    }
    public function attentRegister(Request $request)
    {
        $data = $request->only('name', 'email', 'password', 'password_confirmation');
        $validator = new Validator();
        $validator->validateName($data['name']);
        $validator->validateEmail($data['email']);
        $validator->validatePassword($data['password']);
        $validator->validatePasswordConfirmation($data['password'], $data['password_confirmation']);

        if (!$validator->hasErrors()) {
            $inputsOld = array_map(function ($campo) {
                Validator::sanitizeInput($campo);
                return $campo;
            }, ['name' => $data['name'], 'email' => $data['email']]);
            return redirect()->route('register')->with(['error' => $validator->getErrors(), 'old' => $inputsOld]);
        } else {
            $result = User::createUser($data);

            if ($result['result']) {
                return redirect()->route('login');
            } else {
                return redirect()->route('register')->with('error', ['errorDB' => $result['message']]);
            }
        }
    }
}
