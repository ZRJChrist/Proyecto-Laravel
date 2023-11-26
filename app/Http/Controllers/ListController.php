<?php

namespace App\Http\Controllers;

use App\Models\ConectDB;
use App\Models\User;
use App\Models\Task;
use App\Models\SessionManager;
use App\Models\Provinces;
use DateTime;

class ListController
{
    public function get(?int $page = 1)
    {
        $reg = 6;
        $limit['init'] = ($page - 1) * $reg;
        $limit['reg'] = $reg;
        $totalpag = ceil(Task::numRegister() / $reg);
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

                return view('content.update')->with(['listProvinces' => Provinces::getProvinces(), 'task' => $task['data'][0]]);
            } else {
                // ? Redirigir con alguna informacion (feedbakc) para el usuario ?
                return redirect()->route('listTask');
            }
        } else {
            // ? Redirigir si no se encuentra un usuario. (feedbakc)? 
            return redirect()->route('home');
        }
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
