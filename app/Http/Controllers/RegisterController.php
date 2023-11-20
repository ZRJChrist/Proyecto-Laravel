<?php

namespace App\Http\Controllers;

use App\Models\Validator;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController
{
    public function getLogin()
    {
        session_start();
        if (isset($_SESSION['user_id'])) {
            return redirect()->route('home');
        } else {
            return view('register');
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
            return redirect()->route('register')->with('error', $validator->getErrors());
        } else {
            $result = User::createUser($data);

            if ($result['success']) {
                return redirect()->route('login');
            } else {
                //dd($result);
                return redirect()->route('register')->with('error', ['errorDB' => $result['message']]);
            }
        }
    }
}
