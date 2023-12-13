<?php

namespace App\Http\Controllers;

use App\Models\SessionManager;
use Illuminate\Http\Request;
use App\Helpers\Utils;
use App\Models\User;
use App\Models\Validator;
use Illuminate\Support\Facades\Hash;

/**
 * Autor: @ZRJChrist
 *
 * Descripción: Controlladro que maneja operaciones relacionadas con usuarios, como crear, 
 * leer, actualizar y eliminar usuarios. También realiza validaciones de datos y verifica la autenticación y 
 * permisos del usuario antes de realizar operaciones específicas. 
 * 
 * Fecha de creación: 8/12/2023
 * 
 */
class UserController
{
    //Indica el numero de registros se mostraran por pagina
    private static $reg = 3;
    // Indica si hay un usuario autenticado actualmente.
    private $existsUser;
    //Indica el nombre que tendran en la tabla los roles
    private const role = [
        '0' => 'Operario',
        '1' => 'Administrador',
    ];
    public function __construct()
    {
        // Constructor que se ejecuta al instanciar la clase.
        // Verifica si existe un usuario en la sesión.
        $this->existsUser = SessionManager::read('user') !== false ?  true : false;
    }
    /**
     * Muestra la vista para crear un nuevo usuario.
     * */
    public function createUserView()
    {
        // Redirige al login si no hay usuario en la sesión.
        if (!$this->existsUser) {
            return redirect()->route('login');
        }
        // Redirige a la página anterior si el usuario no es administrador.
        if (!Utils::isAdmin()) {
            return back();
        }
        return view('content.users.add');
    }
    /**
     * Función para crear un nuevo usuario.
     * @param \Illuminate\Http\Request $request Datos del formulario de creacion de usuario.
     */
    public  function createUser(Request $request)
    {
        // Se crea una instancia de Validator para validar los datos.
        $validator = new Validator();
        // Se obtienen los datos del formulario excluyendo el token CSRF.
        $data = $request->except('_token');

        // Validación de los campos del formulario.
        $validator->validateName($data['name']);
        $validator->validateName($data['last_name'], 'last_name');
        $validator->validateNifcif($data['nif_cif'], 'nif_cif');
        $validator->validateEmail($data['email']);
        $validator->validatePhoneNumber($data['phoneNumber'], 'phoneNumber');
        $validator->validateRole($data['role'], 'role');
        $validator->validatePassword($data['password']);
        $validator->validatePasswordConfirmation($data['password'], $data['password_confirmation'], 'password_confirmation');
        /**Si hay algun error, se hara un saneamiento/limpieza a los inputs enviados para devolverlos,
         * tambien se enviara el controllador de error de su validacion para poder expresar en la vista los error que tiene el usuario
         */

        // Si hay errores, se limpian los datos y se devuelven junto con los errores.
        if (!$validator->hasErrors()) {
            $inputsOld = array_map(function ($campo) {
                Validator::sanitizeInput($campo);
                return $campo;
            }, [
                'name' => $data['name'], 'last_name' => $data['last_name'], 'email' => $data['email'],
                'nif_cif' => $data['nif_cif'], 'phoneNumber' => $data['phoneNumber'], 'role' => $data['role']
            ]);
            return to_route('createUsersView')->with(['error' => $validator->getErrorHandler(), 'old' => $inputsOld]);
        } else {
            // Si no hay errores, se intenta crear el usuario en la base de datos.
            $result = User::create($data);

            // Si la creación es exitosa, redirige a la vista de creación de usuarios.
            // En caso de error, se devuelve un mensaje de error.
            if ($result['result']) {
                return to_route('createUsersView');
            } else {
                return to_route('createUsersView')->with('errorDB', $result['message']);
            }
        }
    }
    /**
     * Metodo para la solicitud de la URL 'Users/{id?}'
     * @param int|null $page Número de la página actual. Por defecto, es 1.     
     */
    public function readUserView(?int $page = 1)
    {
        // Verifica si no hay un usuario en la sesión y redirige al login si es el caso.
        if (!$this->existsUser) {
            return redirect()->route('login');
        }
        // Verifica si el usuario no es un administrador y redirige a la página anterior si es el caso.
        if (!Utils::isAdmin()) {
            return back();
        }
        // Inicializa un array para almacenar los parámetros de búsqueda.
        $params = [];
        // Define los parámetros que se van a verificar en la URL.
        $paramsToCheck = ['id', 'role'];
        // Recorre los parámetros definidos y los agrega al array $params si están presentes en la URL.
        foreach ($paramsToCheck as $paramName) {
            if (isset($_GET[$paramName])) {
                $params[$paramName] = $_GET[$paramName];
            }
        }
        // Define el límite de registros por página.
        $limit['reg'] = self::$reg;
        $limit['init'] = ($page - 1) * self::$reg;

        // Obtiene el número total de registros según los parámetros de búsqueda.
        $numReg = User::numRegister($params);

        // Si no hay registros con los parámetros actuales, reinicia los parámetros y vuelve a obtener el número total de registros.
        // ? Seria mejor mostrar la tabla vacia
        if ($numReg == 0) {
            $params = [];
            $numReg = User::numRegister($params);
        }
        // Calcula el número total de páginas necesarias para mostrar todos los registros.
        $totalpag = ceil($numReg / self::$reg);

        // Ajusta el índice inicial si la página solicitada es mayor o igual al total de páginas.
        if ($page >= $totalpag) {
            $limit['init'] = ($totalpag - 1) * self::$reg;
            $page = $totalpag;
        }

        // Retorna la vista 'content.users.read' con los datos necesarios para mostrar la tabla de usuarios.
        return view('content.users.read')->with([
            'users' => self::getUsersTable($limit, $params),
            'page' => $page,
            'total' => $totalpag,
            'params' => $params
        ]);
    }

    public function updateUserView($id)
    {
        // Verifica si no hay un usuario en la sesión y redirige al login si es el caso.
        if (!$this->existsUser) {
            return redirect()->route('login');
        }
        // Verifica si el usuario actual tiene autorización para ver la vista de actualización.
        if (!Utils::isUserAuthorized(SessionManager::read('user', 'id'))) {
            return back();
        }
        // Define los campos de datos que se deben recuperar para el usuario específico.
        $data = [
            'id', 'role', 'name', 'last_name', 'email', 'nif_cif', 'phoneNumber'
        ];
        // Busca el usuario en la base de datos utilizando el ID proporcionado.
        $user = User::find($id, $data)['data'];

        // Retorna la vista con los datos del usuario específico.
        return view('content.users.update')->with(['user' => $user]);
    }

    public function updateUser($id, Request $request)
    {
        // Inicializa una variable para el validador.
        $validator = '';

        // Excluye el token CSRF del conjunto de datos de la solicitud.
        $request = $request->except('_token');

        // Verifica si se ha enviado el formulario de actualización de datos y no el de contraseña.
        if (isset($request['btnUpdateData']) && !isset($request['btnUpdatePass'])) {
            // Redirige a la vista de lectura de usuarios si se ha cancelado la actualización.
            if ($request['btnUpdateData'] == 0) {
                return to_route('readUsers');
            }
            // Valida los datos de actualización del usuario.
            $validator = self::validateUpdateUserData($request);
        } else {
            // Valida la actualización de la contraseña del usuario.
            $validator = self::validateUpdateUserPassword($request);
        }
        // Aplica la limpieza de datos a todos los campos de la solicitud.
        $inputs = array_map(function ($campo) {
            Validator::sanitizeInput($campo);
            return $campo;
        }, $request);

        // Si hay errores en la validación, redirige a la vista de edición con los errores y los datos antiguos.
        if (!$validator->hasErrors()) {
            return to_route('editUser', ['id' => $id])->with(['error' => $validator->getErrorHandler(), 'old' => $inputs]);
        }
        // Actualiza los campos del usuario y redirige a la vista de lectura de usuarios.
        $this->updateUserFields($id, $inputs, $request);
        return to_route('readUsers');
    }
    /**
     * Metodo que realiza la actulizacion de los datos del usuario
     * @param int $id id del usuario a actualizar
     * @param mixed $inputs datos a actualizar
     * @param array $request Datos del formulario.
     */
    private function updateUserFields($id, $inputs, $request)
    {
        // Verifica si se está actualizando la información del usuario y no la contraseña.
        if (isset($request['btnUpdateData']) && !isset($request['btnUpdatePass'])) {
            // Elimina el campo de actualización de datos y realiza la actualización en la base de datos.
            unset($inputs['btnUpdateData']);
            User::update($id, $inputs);
        } else {
            // Elimina el campo de actualización de contraseña y realiza la actualización en la base de datos.
            unset($inputs['btnUpdatePass']);
            $passHash = ['password' => Hash::make($inputs['password'], ['rounds' => 12])];
            User::update($id, $passHash);
        }
    }

    public function deleteUserView()
    {
        if (!$this->existsUser) {
            return redirect()->route('login');
        }
        if (!Utils::isAdmin()) {
            return back();
        }
    }
    public function deleteUser(Request $request)
    {
    }

    private static function getUsersTable($limit, $param = false)
    {
        // Define los campos de datos que se deben recuperar de la base de datos.
        $dataToTable = [
            'id',
            'role',
            'name',
            'phoneNumber',
            'email',
            'last_name',
            'created_at',
            'updated_at'
        ];
        // Inicializa una variable de consulta con todos los usuarios o con parámetros de búsqueda.
        if (!$param) {
            $query = User::getAll($dataToTable, $limit);
        } else {
            $query = User::getAll($dataToTable, $limit, $param);
        }
        // Verifica si la consulta fue exitosa.
        if ($query['result']) {
            // Agrega una descripción del rol a cada usuario en el resultado de la consulta.
            foreach ($query['data'] as &$user) {
                $user['roleDescription'] = self::role[$user['role']];
            }
            // Retorna los datos resultantes de la consulta con las descripciones de roles adicionales.
            return $query['data'];
        } else {
            // Si la consulta no fue exitosa, devuelve falso.
            return false;
        }
    }

    /**
     * Función para validar los datos del formulario update usuario.
     *
     * @param array $request Datos del formulario.
     * @return \App\Models\Validator Objeto validador con los errores (si los hay).
     */
    private function validateUpdateUserData($request)
    {
        // Crea una instancia del validador.
        $validator = new Validator();

        // Validación de campos individuales.
        $validator->validateName($request['name'], 'name');
        $validator->validateName($request['last_name'], 'last_name');
        $validator->validateNifcif($request['nif_cif'], 'nif_cif');
        $validator->validatePhoneNumber($request['phoneNumber'], 'phoneNumber');
        $validator->validateEmail($request['email'], 'email');

        // Devuelve la instancia del validador con los resultados de las validaciones.
        return $validator;
    }
    private function validateUpdateUserPassword($request)
    {
        // Crea una instancia del validador.
        $validator = new Validator();
        // Valida la nueva contraseña del usuario.
        $validator->validatePassword($request['password'], 'password');
        // Valida la confirmación de la nueva contraseña del usuario.
        $validator->validatePasswordConfirmation($request['password'], $request['confirm_password'], 'confirm_password');
        // Devuelve la instancia del validador con los resultados de las validaciones.
        return $validator;
    }
}
