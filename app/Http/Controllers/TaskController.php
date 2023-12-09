<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use Illuminate\Http\Request;
use DateTime;
use App\Models\User;
use App\Models\Task;
use App\Models\SessionManager;
use App\Models\Provinces;
use App\Models\Validator;

class TaskController
{
    //var $reg, representa el numeros de tareas que se mostraran por pantalla
    private static $reg = 3;
    //var $existsUser, representa si hay una sesion de usuario, el valor sera confirmado en el constructor 
    private $existsUser;
    //const status, representa que significa el valor guardado 'status_task' en la base de datos
    private const  status = [
        'B' => 'Esperando ser aprobada',
        'P' => 'Pendiente',
        'R' => 'Realizada',
        'C' => 'Cancelada'
    ];
    /**Contructor del controllador  */
    public function __construct()
    {
        $this->existsUser = SessionManager::read('user') !== false ?  true : false;
    }

    /**Vista para crear una tarea */
    public function createTaskView()
    {
        if (!$this->existsUser) {
            return redirect()->route('login');
        }
        if (!Utils::isAdmin()) {
            return back();
        }
        return view('content.tasks.add')->with(['listProvinces' => Provinces::getProvinces(), 'operarios' => User::getAllOperarios()]);
    }

    /**Funcion que recibe los valores enviados del formulario de agregar tareas */
    public function createTask(Request $request)
    {
        if ($request['btnFrom'] != 0) {
            $inputs = $request->except('_token');

            $formValidado = self::validateForm($inputs);

            if (!$formValidado->hasErrors()) {
                unset($inputs['archivePdf']);
                unset($inputs['archiveImg']);
                $inputsOld = array_map(function ($campo) {
                    Validator::sanitizeInput($campo);
                    return $campo;
                }, $inputs);
                return redirect()->route('addTask')->with(['error' => $formValidado->getErrorHandler(), 'old' => $inputsOld]);
            } else {

                $task_id = Task::lastID() + 1;

                if (isset($_FILES['archivePdf']) && $_FILES['archivePdf']['error'] === UPLOAD_ERR_OK) {
                    $inputs['archivePdf'] = self::AddArchive($task_id, 'archivePdf', 'pdf');
                }
                if (isset($_FILES['archiveImg']) && $_FILES['archiveImg']['error'] === UPLOAD_ERR_OK) {
                    $inputs['archiveImg'] = self::AddArchive($task_id, 'archiveImg', 'img');
                }
                //*Eliminar inputs innecesarios
                unset($inputs['btnFrom']);

                if (Task::create($inputs)) {
                    return redirect()->route('readTasks');
                } else {
                    return redirect()->route('addTask');
                }
            }
        } else {
            return redirect()->route('readTasks');
        }
    }
    /**Vista para listar las tareas */
    public function readTaskTableView(?int $page = 1)
    {
        if (!$this->existsUser) {
            return redirect()->route('login');
        }

        $params = [];

        $paramsToCheck = ['task_id', 'status_task', 'operario'];

        foreach ($paramsToCheck as $paramName) {
            if (isset($_GET[$paramName])) {
                $params[$paramName] = $_GET[$paramName];
            }
        }

        $limit['reg'] = self::$reg;
        $limit['init'] = ($page - 1) * self::$reg;
        $numReg = Task::numRegister($params);

        if ($numReg == 0) {
            $params = [];
            $numReg = Task::numRegister($params);
        }

        $totalpag = ceil($numReg / self::$reg);

        if ($page >= $totalpag) {
            $limit['init'] = ($totalpag - 1) * self::$reg;
            $page = $totalpag;
        }

        $task = self::getTasksTable($limit, $params);

        return view('content.tasks.table')->with([
            'tasks' => $task,
            'page' => $page,
            'total' => $totalpag,
            'params' => $params
        ]);
    }

    /**Vista para editar las tareas */
    public function updateTaskView($id)
    {
        if (!$this->existsUser) {
            return redirect()->route('login');
        }
        $task = Task::find($id);
        if ($task['result']) {
            $id = SessionManager::read('user', 'id');
            $role = SessionManager::read('user', 'role');
            if ($id != $task['data']['operario'] && $role != 1) {
                return back();
            }
            // * Cambiar el tipo de dato de DateTime a Date *  
            $date = new DateTime($task['data']['date_task']);
            $task['data']['date_task'] =  htmlspecialchars($date->format("Y-m-d"));

            return view('content.tasks.update')->with(['listProvinces' => Provinces::getProvinces(), 'task' => $task['data'], 'operarios' => User::getAllOperarios()]);
        } else {
            return redirect()->route('readTasks');
        }
    }
    /**Funcion que recibe la id de la tarea y los datos a actualizar */
    public function updateTask($id, Request $request)
    {
        if ($request['btnFrom'] != 0) {

            $inputs = $request->except('_token');
            //*Eliminar input innecesario para el update
            unset($inputs['btnFrom']);

            $inputs = array_map(function ($campo) {
                Validator::sanitizeInput($campo);
                return $campo;
            }, $inputs);
            $formValidado = self::validateForm($inputs);
            if (!$formValidado->hasErrors()) {
                unset($inputs['archivePdf']);
                unset($inputs['archiveImg']);
                return redirect()->route('editask', ['id' => $id])->with(['error' => $formValidado->getErrorHandler(), 'old' => $inputs]);
            } else {
                $id_user = SessionManager::read('user', 'id');
                $id_operario = Task::find($id, ['operario'])['data']['operario'];
                self::handleFileUploads($id, $inputs);

                if (Utils::isAdmin()) {
                    Task::update($id, $inputs);
                } elseif ($id_user == $id_operario[0]) {
                    Task::update($id, ['status_task' => $inputs['status_task'], 'feedback_task' => $inputs['feedback_task'], 'date_task' => $inputs['date_task'], 'archivePdf' => $inputs['archivePdf'], 'archiveImg' => $inputs['archiveImg']]);
                }
                return to_route('readTasks');
            }
        } else {
            return to_route('readTasks');
        }
    }
    // Función para manejar la carga de archivos
    private function handleFileUploads($id, &$inputs)
    {
        if (isset($_FILES['archivePdf']) && $_FILES['archivePdf']['error'] === UPLOAD_ERR_OK) {
            $inputs['archivePdf'] = self::AddArchive($id, 'archivePdf', 'pdf');
        }

        if (isset($_FILES['archiveImg']) && $_FILES['archiveImg']['error'] === UPLOAD_ERR_OK) {
            $inputs['archiveImg'] = self::AddArchive($id, 'archiveImg', 'img');
        }
    }

    /**Muestra todos los datos de la tarea seleccionada */
    public function readTaskDetailsView($id)
    {
        if (!$this->existsUser) {
            return redirect()->route('login');
        }
        $task = Task::find($id);
        if ($task['result']) {
            $task = $task['data'];
            $task['nombreOperario'] =  User::getAllOperarios()[$task['operario']];
            $task['province_id'] = Provinces::getProvinces()[$task['province_id']];
            $task['statusDescription'] = self::status[$task['status_task']];
            return view('content.tasks.show')->with('task', $task);
        } else {
            return back();
        }
    }

    /**Vista para la confirmacionde de la eliminacion de una tarea */
    public function deleteTaskView($id)
    {
        if (!$this->existsUser) {
            return redirect()->route('login');
        }
        if (!Utils::isAdmin()) {
            return back();
        }
        $task = Task::find($id);
        if ($task['result']) {
            $task = $task['data'];
            $task['operario'] = User::getAllOperarios()[$task['operario']];
            $task['province_id'] = Provinces::getProvinces()[$task['province_id']];
            $task['statusDescription'] = self::status[$task['status_task']];
            return view('content.tasks.confirm')->with('task', $task);
        }
    }
    /**Funcion que elimina la tarea despues de la confirmacion */
    public function deleteTask($id, Request $request)
    {

        if ($request['btnFrom'] != 0) {
            $delete = Task::delete($id);
            if ($delete['result']) {
                return redirect()->route('readTasks');
            } else {
                return redirect()->route('showTask', ['id' => $id]);
            }
        } else {
            return redirect()->route('showTask', ['id' => $id]);
        }
    }
    /**Funcion que, crea la carpeta donde se guardaran los archivos de la tarea si es
     * que no existe, cambia el nombre de los archivos subidos y guarda los archivos
     * en a carpeta de su respectiva tarea 
     */
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

    public function getArchive($id)
    {
        $name = isset($_GET['name']) ? $_GET['name'] : false;
        $pdfPath = storage_path('app\\private\\Task' . $id . '\\' . $name);
        if (file_exists($pdfPath)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($pdfPath) . '"');
            readfile($pdfPath);
            exit;
        } else {
            return redirect()->route('showTask', ['id' => $id]);
        }
    }

    /**Funcion que tomas los datos de las tareas para la tabla, se creara un array
     * donde alamacenas los datos recibidos y se modificaran para mejor experiencia
     * para el usuario 
     */
    private static function getTasksTable($limit, $param = false)
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
        if (!$param) {
            $query = Task::getAll($dataToTable, $limit);
        } else {
            $query = Task::getAll($dataToTable, $limit, $param);
        }
        if ($query['result']) {
            $provinces = Provinces::getProvinces();
            foreach ($query['data'] as &$task) {
                $task['statusDescription'] = self::status[$task['status_task']];
                $task['province_id'] = $provinces[$task['province_id']];
            }
            return $query['data'];
        }
    }
    private function validateForm($request)
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
