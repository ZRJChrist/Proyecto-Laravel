<?php

namespace App\Http\Controllers;

use App\Models\SessionManager;
use Illuminate\Http\Request;
use App\Helpers\Utils;
use App\Models\User;
use App\Models\Validator;
use Illuminate\Support\Facades\Hash;

class UserController
{
    private static $reg = 3;
    private $existsUser;
    private const role = [
        '0' => 'Operario',
        '1' => 'Administrador',
    ];
    public function __construct()
    {
        $this->existsUser = SessionManager::read('user') !== false ?  true : false;
    }

    public function createUserView()
    {
        if (!$this->existsUser) {
            return redirect()->route('login');
        }
        if (!Utils::isAdmin()) {
            return back();
        }
        return view('content.users.add');
    }
    public  function createUser(Request $request)
    {
        $validator = new Validator();
        /**Verificamos que los campos tengan el formato deseado y no se encuentren vacios*/
        $data = $request->except('_token');
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
            /**Si no hay errores en los datos enviara una consulta para la creacion del usuario,
             * esta funcion se encargara de verificar los datos en la base de datos y crear el usuario
             */
            $result = User::create($data);

            /**Si la consulta se ha ejecutado con exito no llevara a la ruta de login, en el caso
             * de algun error se devolvera informacion al usuario
             */
            if ($result['result']) {
                return to_route('createUsersView');
            } else {
                return to_route('createUsersView')->with('errorDB', $result['message']);
            }
        }
    }
    public function readUserView(?int $page = 1)
    {

        if (!$this->existsUser) {
            return redirect()->route('login');
        }
        if (!Utils::isAdmin()) {
            return back();
        }
        $params = [];

        $paramsToCheck = ['id', 'role'];

        foreach ($paramsToCheck as $paramName) {
            if (isset($_GET[$paramName])) {
                $params[$paramName] = $_GET[$paramName];
            }
        }
        $limit['reg'] = self::$reg;
        $limit['init'] = ($page - 1) * self::$reg;
        $numReg = User::numRegister($params);
        $totalpag = ceil($numReg / self::$reg);

        if ($page >= $totalpag) {
            $limit['init'] = ($totalpag - 1) * self::$reg;
            $page = $totalpag;
        }

        return view('content.users.read')->with([
            'users' => self::getUsersTable($limit, $params),
            'page' => $page,
            'total' => $totalpag,
            'params' => $params
        ]);
    }
    public function updateUserView($id)
    {
        if (!$this->existsUser) {
            return redirect()->route('login');
        }
        if (!Utils::isUserAuthorized(SessionManager::read('user', 'id'))) {
            return back();
        }
        $data = [
            'id', 'role', 'name', 'last_name', 'email', 'nif_cif', 'phoneNumber'
        ];
        $user = User::find($id, $data)['data'];
        return view('content.users.update')->with(['user' => $user]);
    }
    public function updateUser($id, Request $request)
    {
        $validator = '';
        $request = $request->except('_token');
        if (isset($request['btnUpdateData']) && !isset($request['btnUpdatePass'])) {
            if ($request['btnUpdateData'] == 0) {
                return to_route('readUsers');
            }
            $validator = self::validateUpdateUserData($request);
        } else {
            $validator = self::validateUpdateUserPassword($request);
        }
        $inputs = array_map(function ($campo) {
            Validator::sanitizeInput($campo);
            return $campo;
        }, $request);
        if (!$validator->hasErrors()) {
            return to_route('editUser', ['id' => $id])->with(['error' => $validator->getErrorHandler(), 'old' => $inputs]);
        }
        $this->updateUserFields($id, $inputs, $request);
        return to_route('readUsers');
    }
    private function updateUserFields($id, $inputs, $request)
    {
        if (isset($request['btnUpdateData']) && !isset($request['btnUpdatePass'])) {
            unset($inputs['btnUpdateData']);
            User::update($id, $inputs);
        } else {
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
        if (!$param) {
            $query = User::getAll($dataToTable, $limit);
        } else {
            $query = User::getAll($dataToTable, $limit, $param);
        }
        if ($query['result']) {
            foreach ($query['data'] as &$user) {
                $user['roleDescription'] = self::role[$user['role']];
            }
            return $query['data'];
        } else {
            return back();
        }
    }
    private function validateUpdateUserData($request)
    {
        $validator = new Validator();
        $validator->validateName($request['name'], 'name');
        $validator->validateName($request['last_name'], 'last_name');
        $validator->validateNifcif($request['nif_cif'], 'nif_cif');
        $validator->validatePhoneNumber($request['phoneNumber'], 'phoneNumber');
        $validator->validateEmail($request['email'], 'email');
        return $validator;
    }
    private function validateUpdateUserPassword($request)
    {
        $validator = new Validator();
        $validator->validatePassword($request['password'], 'password');
        $validator->validatePasswordConfirmation($request['password'], $request['confirm_password'], 'confirm_password');
        return $validator;
    }
}
