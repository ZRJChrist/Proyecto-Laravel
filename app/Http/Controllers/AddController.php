<?php

namespace App\Http\Controllers;

use App\Models\ConectDB;
use PDO;
use App\Models\SessionManager;
use App\Models\Validator;
use App\Models\Task;
use App\Models\User;
use App\Models\Provinces;
use Illuminate\Http\Request;

class AddController
{
    public function get()
    {
        if (SessionManager::read('user_id')) {
            $user_id = SessionManager::read('user_id');
            SessionManager::write('user', User::getDataUser($user_id));
            return view('content.add')->with(['listProvinces' => Provinces::getProvinces(), 'operarios' => User::getAllOperarios()]);
        } else {
            return redirect()->route('home');
        }
    }
    public function post(Request $request)
    {
        if ($request['btnFrom'] != 0) {
            $inputs = $request->except('_token');
            $formValidado = self::validateFormAdd($inputs);

            if (!$formValidado->hasErrors()) {
                $inputsOld = array_map(function ($campo) {
                    Validator::sanitizeInput($campo);
                    return $campo;
                }, $inputs);

                return redirect()->route('addTask')->with(['error' => $formValidado->getErrorHandler(), 'old' => $inputsOld]);
            } else {
                $inputs['user_id'] = SessionManager::read('user_id');
                if (Task::create($inputs)) {
                    return redirect()->route('listTask');
                } else {
                    return redirect()->route('addTask');
                }
            }
        } else {
            return redirect()->route('listTask');
        }
    }
    private static function validateFormAdd($request)
    {
        $validator = new Validator();

        $validator->validateName($request['firstName'], 'firstName');
        $validator->validateName($request['lastName'], 'lastName');
        $validator->validateNifcif($request['nif_cif'], 'nif_cif');
        $validator->validatePhoneNumber($request['phoneNumber'], 'phoneNumber');
        $validator->validateEmail($request['email'], 'email');
        $listProvinces = Provinces::getProvinces();
        $validator->validateProvinces($request['province_id'], $listProvinces, 'province_id');
        $validator->validatePostalCode($request['codigoPostal'], $request['province_id'], $listProvinces, 'codigoPostal');
        $validator->validateOperario($request['operario'], User::getAllOperarios());
        $validator->validateStatus($request['status_task'], 'status_task');
        $validator->validateDate($request['date_task'], 'date_task');
        $validator->validateText($request['direccion'], 'direccion');
        $validator->validateText($request['location'], 'location');
        $validator->validateText($request['description'], 'description');
        return $validator;
    }
}
