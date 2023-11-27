<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use App\Models\ConectDB;
use App\Models\User;
use App\Models\Task;
use App\Models\SessionManager;
use App\Models\Provinces;
use App\Models\Validator;

class ListController
{
    public function get(?int $page = 1)
    {
        $reg = 6;
        $limit['init'] = ($page - 1) * $reg;
        $limit['reg'] = $reg;

        $totalpag = ceil(Task::numRegister() / $reg);
        // *Si la pagina es mayor a las totales, se mostrara siempre la ultima
        if ($page >= $totalpag) {
            $limit['init'] = ($totalpag - 1) * $reg;
            $page = $totalpag;
        }

        if (SessionManager::read('user_id')) {
            $user_id = SessionManager::read('user_id');
            SessionManager::write('user', User::getDataUser($user_id));
            return view('content.table')->with(['tasks' => self::getTasksTable($limit), 'page' => $page, 'total' => $totalpag]);
        } else {
            return redirect()->route('home');
        }
    }
    public function edit($id)
    {
        if (SessionManager::read('user_id')) {
            $user_id = SessionManager::read('user_id');
            $task = Task::find($id);
            if ($task['result']) {
                SessionManager::write('user', User::getDataUser($user_id));

                // * Cambiar tipo de datos de DateaTime a Date; *
                $date = new DateTime($task['data'][0]['date_task']);
                $task['data'][0]['date_task'] =  htmlspecialchars($date->format("Y-m-d"));

                return view('content.update')->with(['listProvinces' => Provinces::getProvinces(), 'task' => $task['data'][0], 'operarios' => User::getAllOperarios()]);
            } else {
                // ? Redirigir con alguna informacion (feedbakc) para el usuario ?
                return redirect()->route('listTask');
            }
        } else {
            // ? Redirigir si no se encuentra un usuario. (feedbakc)? 
            return redirect()->route('home');
        }
    }
    public function update($id, Request $request)
    {
        if ($request['btnFrom'] != 0) {
            $inputs = $request->except('_token');
            $formValidado = self::validateFromUpdate($inputs);
            if (!$formValidado->hasErrors()) {
                unset($inputs['archivePdf']);
                unset($inputs['archiveImg']);
                $inputsOld = array_map(function ($campo) {
                    Validator::sanitizeInput($campo);
                    return $campo;
                }, $inputs);

                return redirect()->route('editask', ['id' => $id])->with(['error' => $formValidado->getErrorHandler(), 'old' => $inputsOld]);
            }
        } else {
            return redirect()->route('listTask');
        }
    }
    private function validateFromUpdate($request)
    {
        $validator = new Validator();
        $validator->validateText($request['description'], 'description');
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
        if (isset($request['archivePdf']))
            $validator->validateArchive($request['archivePdf']->getMimeType(), 'pdf', 'archivePdf');
        if (isset($request['archiveImg']))
            $validator->validateArchive($request['archiveImg']->getMimeType(), 'image', 'archiveImg');

        return $validator;
    }
    public function show($id)
    {
    }
    public function delete($id)
    {
    }

    private static function getTasksTable($limit)
    {
        $dataToTable = [
            'task_id',
            'firstName',
            'lastName',
            'nif_cif',
            'phoneNumber',
            'email',
            'description',
            'codigoPostal',
            'location',
            'province_id',
            'status_task',
            'DATE(date_task) as date_task',
            'operario',
            'inf_task'
        ];
        $query = Task::getAll($dataToTable, $limit);
        define('status', [
            'B' => 'Esperando ser aprobada',
            'P' => 'Pendiente',
            'R' => 'Realizada',
            'C' => 'Cancelada'
        ]);
        if ($query['result']) {
            $provinces = Provinces::getProvinces();
            foreach ($query['data'] as &$task) {
                $task['statusDescription'] = status[$task['status_task']];
                $task['province_id'] = $provinces[$task['province_id']];
            }
            return $query['data'];
        } else {
            //dd($data['message']);
        }
    }
}
