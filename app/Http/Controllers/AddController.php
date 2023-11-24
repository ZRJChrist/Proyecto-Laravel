<?php

namespace App\Http\Controllers;

use App\Models\ConectDB;
use App\Models\SessionManager;
use App\Models\Validator;
use Illuminate\Http\Request;
use App\Models\Task;
use PDO;

class AddController
{
    public function get()
    {
        if (SessionManager::read('user_id')) {
            $user_id = SessionManager::read('user_id');
            SessionManager::write('user', self::getDataUser($user_id));
            return view('content.add')->with('listProvinces', self::getProvinces());
        } else {
            return redirect()->route('home');
        }
    }
    public function post(Request $request)
    {
        $inputs = $request->except('_token');
        $formValidado = self::validateFormAdd($inputs);

        if (!$formValidado->hasErrors()) {
            return redirect()->route('addTask')->with('error', $formValidado->getErrorHandler());
        } else {
            $inputs['user_id'] = SessionManager::read('user_id');
            if (Task::create($inputs)) {
                return redirect()->route('listTask');
            } else {
                return redirect()->route('addTask');
            }
        }
    }
    private static function getProvinces()
    {
        $connection = ConectDB::getInstance()->getConnection();
        $query = $connection->prepare('SELECT province_id, name_province FROM provinces');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $listProvinces[$row['province_id']] = $row['name_province'];
        }
        return $listProvinces;
    }
    private static function getDataUser($user_id)
    {
        $connection = ConectDB::getInstance()->getConnection();
        $query = $connection->prepare('SELECT name,last_name, email, phoneNumber, nif_cif  FROM users WHERE id = :id');
        $query->execute([':id' => $user_id]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result[0];
    }
    private static function validateFormAdd($request)
    {
        $validator = new Validator();

        $validator->validateName($request['firstName'], 'firstName');
        $validator->validateName($request['lastName'], 'lastName');
        $validator->validateNifcif($request['nif_cif'], 'nif_cif');
        $validator->validatePhoneNumber($request['phoneNumber'], 'phoneNumber');
        $listProvinces = self::getProvinces();
        $validator->validateProvinces($request['provinces'], $listProvinces, 'provinces');
        $validator->validatePostalCode($request['codigoPostal'], $request['provinces'], $listProvinces, 'codigoPostal');
        $validator->validateStatus($request['status'], 'status');
        $validator->validateDate($request['date_task'], 'date_task');
        $validator->validateText($request['direccion'], 'direccion');
        $validator->validateText($request['location'], 'location');
        $validator->validateText($request['description'], 'description');
        $validator->validateText($request['operario'], 'operario');
        return $validator;
    }
}
