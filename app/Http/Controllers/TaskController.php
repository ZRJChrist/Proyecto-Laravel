<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use App\Models\User;
use App\Models\Task;
use App\Models\SessionManager;
use App\Models\Provinces;
use App\Models\Validator;
use Illuminate\Validation\Rules\Exists;

class TaskController
{
    private static $reg = 6;
    private $existsUser;
    private const  status = [
        'B' => 'Esperando ser aprobada',
        'P' => 'Pendiente',
        'R' => 'Realizada',
        'C' => 'Cancelada'
    ];

    public function __construct()
    {
        $this->existsUser = SessionManager::read('user_id') != null ?  true : false;
    }
    public function get(?int $page = 1)
    {
        if ($this->existsUser) {
            $limit['reg'] = self::$reg;
            $limit['init'] = ($page - 1) * self::$reg;
            $totalpag = ceil(Task::numRegister() / self::$reg);

            // *Si la pagina es mayor a las totales, se mostrara siempre la ultima pagina con datos
            if ($page >= $totalpag) {
                $limit['init'] = ($totalpag - 1) * self::$reg;
                $page = $totalpag;
            }
            SessionManager::write('user', User::getDataUser(SessionManager::read('user_id')));

            return view('content.table')->with(['tasks' => self::getTasksTable($limit), 'page' => $page, 'total' => $totalpag]);
        } else {
            return redirect()->route('home');
        }
    }
    public function edit($id)
    {
        if ($this->existsUser) {
            $task = Task::find($id);
            if ($task['result']) {

                // * Cambiar el tipo de dato de DateTime a Date *  
                $date = new DateTime($task['data']['date_task']);
                $task['data']['date_task'] =  htmlspecialchars($date->format("Y-m-d"));

                return view('content.update')->with(['listProvinces' => Provinces::getProvinces(), 'task' => $task['data'], 'operarios' => User::getAllOperarios()]);
            } else {
                // ? Redirigir con alguna informacion (feedback) para el usuario ?
                return redirect()->route('listTask');
            }
        } else {
            // ? Redirigir si no se encuentra un usuario. (feedback)? 
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
            } else {

                if (isset($_FILES['archivePdf']) && $_FILES['archivePdf']['error'] === UPLOAD_ERR_OK) {
                    $inputs['archivePdf'] = self::AddArchive($id, 'archivePdf', 'pdf');
                }
                if (isset($_FILES['archiveImg']) && $_FILES['archiveImg']['error'] === UPLOAD_ERR_OK) {
                    $inputs['archiveImg'] = self::AddArchive($id, 'archiveImg', 'img');;
                }
                //*Eliminar inputs innecesarios
                unset($inputs['btnFrom']);
                $update = Task::update($id, $inputs);
                if ($update['result']) {
                    return redirect()->route('listTask');
                }
            }
        } else {
            return redirect()->route('listTask');
        }
    }

    public function show($id)
    {
        if ($this->existsUser) {
            SessionManager::write('user', User::getDataUser(SessionManager::read('user_id')));
            $task = Task::find($id);
            if ($task['result']) {
                $task = $task['data'];
                $task['operario'] = User::getAllOperarios()[$task['operario']];
                $task['province_id'] = Provinces::getProvinces()[$task['province_id']];
                $task['statusDescription'] = self::status[$task['status_task']];
                return view('content.show')->with('task', $task);
            }
        } else {
            return redirect()->route('home');
        }
    }
    private function AddArchive($id, $nameArchive, $mimeType)
    {
        //* Busca la carpeta de la tarea. Si esta no existe se le creara una.

        $address = storage_path('app\\private\\') . 'Task' . $id;
        if (!file_exists($address)) {
            mkdir($address, 0777, true);
        }
        //* Extraer la extension del archivo.

        $ext = pathinfo($_FILES[$nameArchive]['name'], PATHINFO_EXTENSION);

        if ($mimeType == 'pdf') {
            $newName = 'PDF-Task' . $id . '.' . $ext;
            $ruta_destino = $address . '\\' . $newName;
            move_uploaded_file($_FILES[$nameArchive]['tmp_name'], $ruta_destino);
            return $newName;
        }
        if ($mimeType == 'img') {
            $newName = time() . '.' . $ext;
            $ruta_destino = $address . '\\' . $newName;
            move_uploaded_file($_FILES[$nameArchive]['tmp_name'], $ruta_destino);
            return 'app\\private\\Task' . $id;
        }
        return false;
    }
    public function confirm($id)
    {
        if (SessionManager::read('user_id')) {
            SessionManager::write('user', User::getDataUser(SessionManager::read('user_id')));
            $task = Task::find($id);
            if ($task['result']) {
                $task = $task['data'];
                $task['operario'] = User::getAllOperarios()[$task['operario']];
                $task['province_id'] = Provinces::getProvinces()[$task['province_id']];
                $task['statusDescription'] = self::status[$task['status_task']];
                return view('content.confirm')->with('task', $task);
            }
        } else {
            return redirect()->route('home');
        }
    }
    public function delete($id)
    {
        $delete = Task::delete($id);
        if ($delete['result']) {
        }
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
            'direccion',
            'province_id',
            'status_task',
            'DATE(date_task) as date_task',
            'operario',
            'inf_task'
        ];
        $query = Task::getAll($dataToTable, $limit);
        if ($query['result']) {
            $provinces = Provinces::getProvinces();
            foreach ($query['data'] as &$task) {
                $task['statusDescription'] = self::status[$task['status_task']];
                $task['province_id'] = $provinces[$task['province_id']];
            }
            return $query['data'];
        } else {
            //dd($data['message']);
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
}
