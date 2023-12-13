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

/**
 * Autor: @ZRJChrist
 *
 * Descripción: Controlador que gestiona las tareas , incluyendo la creación, lectura, actualización, eliminación, y manejo de archivos asociados. También se encargan de validar los datos del formulario y controlar el acceso a las vistas dependiendo del estado de la sesión y los roles de usuario.
 * 
 * @see App/Models/Task
 * Fecha de creación: 23/11/2023
 * 
 */
class TaskController
{
    //Indica el numero de registros se mostraran por pagina
    private static $reg = 6;

    // Indica si hay un usuario autenticado actualmente.
    private $existsUser;

    //Contante que guarda la decripcion de los estados de una tarea
    // ! Seria mas util guardarla en una tabla en la base de datos.
    private const  status = [
        'B' => 'Esperando ser aprobada',
        'P' => 'Pendiente',
        'R' => 'Realizada',
        'C' => 'Cancelada'
    ];

    //Constructor del controlador
    public function __construct()
    {
        // Verifica si hay una sesión iniciada y asigna el valor a $existsUser.
        $this->existsUser = SessionManager::read('user') !== false ?  true : false;
    }

    /**
     * Metodo para la solicitud de la URL 'Task/Add' 
     */
    public function createTaskView()
    {
        //Si no existe usuario no se dara acceso a la pagina
        if (!$this->existsUser) {
            return redirect()->route('login');
        }
        //Verifica si la solicitud es por parte de un administrador
        //Solo los administradores puede acceder a la pagina
        if (!Utils::isAdmin()) {
            return back();
        }
        //En caso de que sea un administrador se llevara a la pagina.
        return view('content.tasks.add')->with(['listProvinces' => Provinces::getProvinces(), 'operarios' => User::getAllOperarios()]);
    }

    /**
     * Método para crear una tarea basado en la información proporcionada en el formulario.
     * @param \Illuminate\Http\Request $request Datos del formulario de creacion.
     */
    public function createTask(Request $request)
    {
        //Verifica el valor del input btnForm. 
        //Este puede ser 0 (cancelar , no crear tarea) ó 1 (Crear tarea) .
        // ! No es muy util hacer esto ya que lleva un paso mas de verificacion.
        // ? Seria mejor que el boton 'cancelar' simplemente redirija a la lista de tareas.
        if ($request['btnFrom'] != 0) {
            // Excluimos el input '_token' creado por '@csrf'
            $inputs = $request->except('_token');
            // Utilizamos el metodo 'ValidateForm' para validar los inputs
            $formValidado = self::validateForm($inputs);

            // Sanitiza los datos del formulario.
            $inputs = array_map(function ($campo) {
                Validator::sanitizeInput($campo);
                return $campo;
            }, $inputs);

            //Comprueba si existe algun error
            if (!$formValidado->hasErrors()) {
                // Dado que existe algun error, se eliminaran los inputs de los archivos por seguridad.
                unset($inputs['archivePdf']);
                unset($inputs['archiveImg']);

                // Se regresa a la vista de agregar tareas con los datos de error y los inputs ingresados
                return redirect()->route('createTaskView')->with(['error' => $formValidado->getErrorHandler(), 'old' => $inputs]);
            } else {
                // Tomaremos el ultimo id de las tareas para la creacion de la carpeta.
                $task_id = Task::lastID() + 1;
                // Si se ha agregado un archivo, se guardara en la carpeta de la tarea correspondiente
                if (isset($_FILES['archivePdf']) && $_FILES['archivePdf']['error'] === UPLOAD_ERR_OK) {
                    $inputs['archivePdf'] = self::AddArchive($task_id, 'archivePdf', 'pdf');
                } else {
                    $inputs['archivePdf'] = NULL;
                }
                if (isset($_FILES['archiveImg']) && $_FILES['archiveImg']['error'] === UPLOAD_ERR_OK) {
                    $inputs['archiveImg'] = self::AddArchive($task_id, 'archiveImg', 'img');
                } else {
                    $inputs['archiveImg'] = NULL;
                }
                //Eliminar input innecesario
                unset($inputs['btnFrom']);
                if (Task::create($inputs)) {
                    return redirect()->route('readTasks');
                } else {
                    return redirect()->route('createTaskView');
                }
            }
        } else {
            return redirect()->route('readTasks');
        }
    }

    /**
     * Metodo para la solicitud de la URL 'Tasks/{id?}'
     * @param int|null $page Número de la página actual. Por defecto, es 1.     
     */
    public function readTaskTableView(?int $page = 1)
    {
        // Verifica si existe una sesión activa. Si no existe, redirige al inicio de sesión.
        if (!$this->existsUser) {
            return redirect()->route('login');
        }
        // Parámetros de filtrado para las tareas.
        $params = [];
        // Parámetros de filtrado permitidos.
        $paramsToCheck = ['task_id', 'status_task', 'operario'];

        // Comprueba que los parámetros de $_GET coincidan con los permitidos.
        foreach ($paramsToCheck as $paramName) {
            // Si el parámetro existe, lo agrega a la variable $params.
            if (isset($_GET[$paramName])) {
                $params[$paramName] = $_GET[$paramName];
            }
        }

        if (!Utils::isAdmin()) {
            $params['operario'] = SessionManager::read('user', 'id');
        }

        // Establece los límites de registros a mostrar.
        $limit['reg'] = self::$reg;
        $limit['init'] = ($page - 1) * self::$reg;

        // Obtiene el número total de registros con los parámetros de filtrado.
        $numReg = Task::numRegister($params);

        // Si no hay registros con los parámetros actuales, reinicia los parámetros y vuelve a obtener el número total de registros.
        if ($numReg == 0) {
            if (!Utils::isAdmin()) {
                $params['operario'] = SessionManager::read('user', 'id');
            }
            $numReg = Task::numRegister($params);
        }
        // Calcula el número total de páginas.
        $totalpag = ceil($numReg / self::$reg);

        // Ajusta la página si es mayor o igual al total de páginas.
        if ($page >= $totalpag) {
            $limit['init'] = ($totalpag - 1) * self::$reg;
            $page = $totalpag;
        }
        // Si el numero de inicio es menor a 0 reajsuta los datos de la tabla
        if ($limit['init'] < 0) {
            $limit['init'] = 0;
            $totalpag = 0;
            $page = 1;
        }
        // Obtiene las tareas según los límites y parámetros establecidos.
        $task = self::getTasksTable($limit, $params);

        // Devuelve la vista de la tabla de tareas con los datos necesarios.
        return view('content.tasks.table')->with([
            'tasks' => $task,
            'page' => $page,
            'total' => $totalpag,
            'params' => $params
        ]);
    }
    /**
     * Metodo para la solicitud de la URL 'Tasks/Update/{id}'
     * @param int $id Identificador de la tarea a actualizar.
     */
    public function updateTaskView($id)
    {
        // Verifica si existe una sesión activa. Si no existe, redirige al inicio de sesión.
        if (!$this->existsUser) {
            return redirect()->route('login');
        }
        // Busca la tarea con el ID proporcionado.
        $task = Task::find($id);
        // Verifica si se encontró la tarea.

        if ($task['result']) {
            // Obtiene la información del usuario actual desde la sesión.
            $id = SessionManager::read('user', 'id');
            $role = SessionManager::read('user', 'role');
            // Verifica si el usuario actual tiene permisos para actualizar la tarea.
            if ($id != $task['data']['operario'] && $role != 1) {
                // Regresa a la página anterior si no tiene permisos.
                return back();
            }
            // Cambia el tipo de dato de DateTime a Date para la fecha de la tarea.
            $date = new DateTime($task['data']['date_task']);
            $task['data']['date_task'] =  htmlspecialchars($date->format("Y-m-d"));

            // Devuelve la vista de actualización de tareas con la información necesaria (lista de las provincias y de los operarios de la base de datos).
            return view('content.tasks.update')->with(['listProvinces' => Provinces::getProvinces(), 'task' => $task['data'], 'operarios' => User::getAllOperarios()]);
        } else {
            // Redirige a la página de listar tareas si la tarea no se encuentra.
            return redirect()->route('readTasks');
        }
    }

    /**
     * Método para actualizar una tarea basado en la información proporcionada en el formulario.
     * @param int $id Identificador de la tarea a actualizar.
     * @param \Illuminate\Http\Request $request Datos del formulario de actualización.
     */
    public function updateTask($id, Request $request)
    {
        //Verifica el valor del input btnForm. 
        //Este puede ser 0 (cancelar , no crear tarea) ó 1 (Crear tarea) .
        // ! No es muy util hacer esto ya que lleva un paso mas de verificacion.
        // ? Seria mejor que el boton 'cancelar' simplemente redirija a la lista de tareas.
        if ($request['btnFrom'] != 0) {
            // Obtiene los datos del formulario excluyendo el token.
            $inputs = $request->except('_token');

            // Elimina el campo 'btnFrom', que no es necesario para la actualización.
            unset($inputs['btnFrom']);

            // Sanitiza los datos del formulario.
            $inputs = array_map(function ($campo) {
                Validator::sanitizeInput($campo);
                return $campo;
            }, $inputs);

            // Valida el formulario y devuelve el resultado.
            $formValidado = self::validateForm($inputs);

            // Si hay errores en la validación del formulario, redirige de nuevo al formulario de edición con los errores y datos antiguos.
            if (!$formValidado->hasErrors()) {
                unset($inputs['archivePdf']);
                unset($inputs['archiveImg']);
                $inputs['task_id'] = $id;
                return view('content.tasks.update')->with(['listProvinces' => Provinces::getProvinces(), 'task' => $inputs, 'operarios' => User::getAllOperarios()]);
            } else {
                // Obtiene el ID del usuario actual y el operario asociado a la tarea.
                $id_user = SessionManager::read('user', 'id');
                $id_operario = Task::find($id, ['operario'])['data']['operario'];

                // Maneja la subida de archivos.
                self::handleFileUploads($id, $inputs);

                // Actualiza la tarea según el rol del usuario.
                if (Utils::isAdmin()) {
                    Task::update($id, $inputs);
                } elseif ($id_user == $id_operario[0]) {
                    Task::update($id, ['status_task' => $inputs['status_task'], 'feedback_task' => $inputs['feedback_task'], 'date_task' => $inputs['date_task'], 'archivePdf' => $inputs['archivePdf'], 'archiveImg' => $inputs['archiveImg']]);
                }
                // Redirige a la página de los detalles de la tarea después de la actualización.
                return to_route('showTask', ['id' => $id]);
            }
        } else {
            // Si no se hizo clic en el botón de actulizar, redirige a la página de listar tareas.
            return to_route('readTasks');
        }
    }
    /**
     * Maneja la subida de archivos para una tarea específica.
     *
     * @param int $id Identificador de la tarea.
     * @param array $inputs Datos del formulario que pueden incluir archivos.
     * @return void
     */
    private function handleFileUploads($id, &$inputs)
    {
        // Verifica si se ha enviado un archivo PDF y no hay errores en la subida.
        if (isset($_FILES['archivePdf']) && $_FILES['archivePdf']['error'] === UPLOAD_ERR_OK) {
            // Agrega el archivo PDF a los datos del formulario.
            $inputs['archivePdf'] = self::AddArchive($id, 'archivePdf', 'pdf');
        } else {
            $inputs['archivePdf'] = NULL;
        }
        // Verifica si se ha enviado una imagen y no hay errores en la subida.
        if (isset($_FILES['archiveImg']) && $_FILES['archiveImg']['error'] === UPLOAD_ERR_OK) {
            // Agrega la imagen a los datos del formulario.
            $inputs['archiveImg'] = self::AddArchive($id, 'archiveImg', 'img');
        } else {
            $inputs['archiveImg'] = NULL;
        }
    }
    /**
     * Función que crea la carpeta donde se guardarán los archivos de la tarea, cambia el nombre de los archivos subidos
     * y guarda los archivos en la carpeta de su respectiva tarea.
     *
     * @param int $id Identificador de la tarea.
     * @param string $nameArchive Nombre del archivo en el formulario.
     * @param string $mimeType Tipo de archivo ('pdf' o 'img').
     * @return string|false Nombre del archivo o false si no se pudo procesar.
     */
    private function AddArchive($id, $nameArchive, $mimeType)
    {
        // Busca la carpeta de la tarea. Si no existe, la crea.
        $address = storage_path('app\\private\\') . 'Task' . $id;
        if (!file_exists($address)) {
            mkdir($address, 0777, true);
        }

        // Extrae la extensión del archivo.
        $ext = pathinfo($_FILES[$nameArchive]['name'], PATHINFO_EXTENSION);

        // Genera un nuevo nombre para el archivo.
        if ($mimeType == 'pdf') {
            $newName = 'PDF-Task' . $id . '.' . $ext;
        } elseif ($mimeType == 'img') {
            $newName = time() . '.' . $ext;
        } else {
            return false; // Tipo de archivo no válido.
        }

        // Ruta completa del destino del archivo.
        $ruta_destino = $address . '\\' . $newName;

        // Mueve el archivo subido a la carpeta de la tarea.
        move_uploaded_file($_FILES[$nameArchive]['tmp_name'], $ruta_destino);

        // Devuelve el nuevo nombre del archivo o la ruta de la carpeta en caso de imagen.
        return ($mimeType == 'pdf') ? $newName : 'app\\private\\Task' . $id;
    }

    /**
     * Método para la visualización detallada de una tarea específica.
     * @param int $id Identificador de la tarea.
     */
    public function readTaskDetailsView($id)
    {
        // Verifica si existe una sesión activa. Si no existe, redirige al inicio de sesión.
        if (!$this->existsUser) {
            return redirect()->route('login');
        }
        // Busca la tarea con el ID proporcionado.
        $task = Task::find($id);

        // Verifica si se encontró la tarea.
        if ($task['result']) {

            // Obtiene los datos de la tarea.
            $task = $task['data'];

            // Agrega información adicional a la tarea.
            $task['nombreOperario'] =  User::getAllOperarios()[$task['operario']];
            $task['province_id'] = Provinces::getProvinces()[$task['province_id']];
            $task['statusDescription'] = self::status[$task['status_task']];

            // Devuelve la vista de detalles de la tarea con los datos necesarios.
            return view('content.tasks.show')->with('task', $task);
        } else {
            // Si no se encontró la tarea, regresa a la página anterior.
            return back();
        }
    }

    /**
     * Método para la visualización de la confirmación de eliminación de una tarea.
     *
     * @param int $id Identificador de la tarea.
     */
    public function deleteTaskView($id)
    {
        // Verifica si existe una sesión activa. Si no existe, redirige al inicio de sesión.
        if (!$this->existsUser) {
            return redirect()->route('login');
        }
        // Verifica si el usuario actual es administrador. Si no lo es, regresa a la página anterior.
        if (!Utils::isAdmin()) {
            return back();
        }
        // Busca la tarea con el ID proporcionado.
        $task = Task::find($id);
        // Verifica si se encontró la tarea.
        if ($task['result']) {

            // Obtiene los datos de la tarea.
            $task = $task['data'];

            // Agrega información adicional a la tarea.
            $task['operario'] = User::getAllOperarios()[$task['operario']];
            $task['province_id'] = Provinces::getProvinces()[$task['province_id']];
            $task['statusDescription'] = self::status[$task['status_task']];
            // Devuelve la vista de confirmación de eliminación de la tarea con los datos necesarios.

            return view('content.tasks.confirm')->with('task', $task);
        } else {
            // Si no se encontró la tarea, regresa a la página anterior.
            return back();
        }
    }
    /**
     * Función que elimina la tarea después de la confirmación.
     *
     * @param int $id Identificador de la tarea a eliminar.
     * @param \Illuminate\Http\Request $request Datos del formulario de confirmación.
     */
    public function deleteTask($id, Request $request)
    {
        // Verifica si se hizo clic en el botón de un formulario externo.
        if ($request['btnFrom'] != 0) {
            // Intenta eliminar la tarea con el ID proporcionado.
            $delete = Task::delete($id);

            // Verifica si la eliminación fue exitosa.
            if ($delete['result']) {
                // Redirige a la página de listar tareas después de la eliminación.
                return redirect()->route('readTasks');
            } else {
                // Si no se pudo eliminar, redirige a la página de detalles de la tarea.
                return redirect()->route('showTask', ['id' => $id]);
            }
        } else {
            // Si no se hizo clic en el botón del formulario externo, redirige a la página de detalles de la tarea.
            return redirect()->route('showTask', ['id' => $id]);
        }
    }

    /**
     * Función para obtener y descargar archivos asociados a una tarea.
     *
     * @param int $id Identificador de la tarea.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getArchive($id)
    {
        // Verifica si existe una sesión activa. Si no existe, redirige al inicio de sesión.
        if (!$this->existsUser) {
            return redirect()->route('login');
        }

        // Obtiene el ID del operario asociado a la tarea.
        $id_operario = Task::find($id, ['operario'])['data']['operario'];

        // Verifica si el usuario actual está autorizado para acceder al archivo.
        if (Utils::isUserAuthorized($id_operario)) {

            // Obtiene el nombre del archivo de la solicitud o establece en falso si no se proporciona.
            $name = isset($_GET['name']) ? $_GET['name'] : false;

            // Ruta completa del archivo PDF asociado a la tarea.
            $pdfPath = storage_path('app\\private\\Task' . $id . '\\' . $name);

            // Verifica si el archivo existe en la ruta especificada.
            if (file_exists($pdfPath)) {
                // Configura las cabeceras para forzar la descarga del archivo.
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="' . basename($pdfPath) . '"');

                // Lee y envía el archivo al navegador.
                readfile($pdfPath);

                // Finaliza la ejecución del script después de enviar el archivo.
                exit;
            } else {

                // Si el archivo no existe, redirige a la página de detalles de la tarea.
                return redirect()->route('showTask', ['id' => $id]);
            }
        } else {
            // Si el usuario no está autorizado, redirige a la página de listar tareas.
            return to_route('readTasks');
        }
    }

    /**
     * Función que obtiene datos de las tareas para su visualización en una tabla.
     *
     * @param array $limit Límite de registros y posición de inicio.
     * @param array|false $param Parámetros de filtrado opcional.
     * @return array|false Datos de las tareas para la tabla o falso en caso de error.
     */
    private static function getTasksTable($limit, $param = false)
    {
        // Campos que se recuperarán de la base de datos para la tabla.
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
        // Obtiene los datos de las tareas desde la base de datos.
        if (!$param) {
            $query = Task::getAll($dataToTable, $limit);
        } else {
            $query = Task::getAll($dataToTable, $limit, $param);
        }
        // Verifica si la consulta fue exitosa.
        if ($query['result']) {
            // Obtiene la lista de provincias.
            $provinces = Provinces::getProvinces();

            // Modifica los datos para mejorar la experiencia del usuario.
            foreach ($query['data'] as &$task) {
                $task['statusDescription'] = self::status[$task['status_task']];
                $task['province_id'] = $provinces[$task['province_id']];
            }

            // Devuelve los datos modificados para la tabla.
            return $query['data'];
        }
        // Si la consulta no fue exitosa, devuelve falso.
        return false;
    }

    /**
     * Función para validar los datos del formulario de tarea.
     *
     * @param array $request Datos del formulario.
     * @return \App\Models\Validator Objeto validador con los errores (si los hay).
     */
    private function validateForm($request)
    {
        // Crea un objeto validador.
        $validator = new Validator();

        // Validación de campos individuales.
        $validator->validateText($request['description'], 'description');
        $validator->validateName($request['firstName'], 'firstName');
        $validator->validateName($request['lastName'], 'lastName');
        $validator->validateNifcif($request['nif_cif'], 'nif_cif');
        $validator->validatePhoneNumber($request['phoneNumber'], 'phoneNumber');
        $validator->validateEmail($request['email'], 'email');

        // Obtiene la lista de provincias.
        $listProvinces = Provinces::getProvinces();

        // Validación específica para provincias y código postal.
        $validator->validateProvinces($request['province_id'], $listProvinces, 'province_id');
        $validator->validatePostalCode($request['codigoPostal'], $request['province_id'], $listProvinces, 'codigoPostal');

        // Validación de operario, estado, fecha y textos adicionales.
        $validator->validateOperario($request['operario'], User::getAllOperarios());
        $validator->validateStatus($request['status_task'], 'status_task');
        $validator->validateDate($request['date_task'], 'date_task');
        $validator->validateText($request['direccion'], 'direccion');
        $validator->validateText($request['location'], 'location');

        // Validación de archivos PDF si están presentes.
        if (isset($request['archivePdf']))
            $validator->validateArchive($request['archivePdf']->getMimeType(), 'pdf', 'archivePdf');

        // Validación de archivos de imagen si están presentes.
        if (isset($request['archiveImg']))
            $validator->validateArchive($request['archiveImg']->getMimeType(), 'image', 'archiveImg');

        // Devuelve el objeto validador con los errores (si los hay).
        return $validator;
    }
}
