<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\SessionManager;
use App\Models\Validator;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController
{
    private $existsUser;

    public function __construct()
    {
        /* Verificar si el hay una sesión iniciada, sino devuelve la varibale ´existUser´ sera false,
        en el caso contrario sera true.
        */
        $this->existsUser = SessionManager::read('user_id') !== false ?  true : false;
    }
    /*
    Funcion que se ejecuta al solicitar la url de login
    */
    public function getSignIn()
    {
        /* Redirige a la lista de tareas si es que existe una sesión*/
        if ($this->existsUser) {
            return redirect()->route('listTask');
        } else {
            return view('content.login');
        }
    }
    /*
    Funcion que se ejecuta al solicitar la url de registro
    */
    public function getSigUp()
    {
        /* Redirige a la lista de tareas si es que existe una sesión*/
        if ($this->existsUser) {
            return redirect()->route('listTask');
        } else {
            return view('content.register');
        }
    }
    /**
     * Funcion que recibira los campos post enviados del formulario login.
     * @param Request $request. Valores de los inputs
     */
    public  function attemptSignIn(Request $request)
    {
        //Excluimos el campo _token debido a que no utilizara en esta caso
        $request = $request->only('email', 'password');

        /*Verificamos si el email se encuentra en la base de datos, sino se encuentra
        nos devolvera un error*/
        if (!User::checkIfExistsEmail($request['email'])) {
            return redirect()->route('login')->with('email', 'Email no registrado');
        }
        /*Verificamos si los datos coinciden con los de la base de datos, en caso si coincidan 
        nos llevara a la pagina de listar tareas*/
        if (self::validateCredentials($request)) {
            SessionManager::write('user_id', User::getUser($request['email']));
            return redirect()->route('listTask');
        } else {
            /**En caso de que las credenciales no coincidan nos devolvera informacion */
            return redirect('login')->with('password', 'Contraseña incorrecta');
        }
    }
    /**
     * Funcion que recibira los campos post enviados del formulario SignUp.
     * @param Request $request. Valores de los inputs
     */
    public function attentSignUp(Request $request)
    {
        $validator = new Validator();
        /**Verificamos que los campos tengan el formato deseado y no se encuentren vacios*/
        $data = $request->only('name', 'email', 'password', 'password_confirmation');
        $validator->validateName($data['name']);
        $validator->validateEmail($data['email']);
        $validator->validatePassword($data['password']);
        $validator->validatePasswordConfirmation($data['password'], $data['password_confirmation'], 'password_confirmation');

        /**Si hay algun error, se hara un saneamiento/limpieza a los inputs enviados para devolverlos,
         * tambien se enviara el controllador de error de su validacion para poder expresar en la vista los error que tiene el usuari
         */
        if (!$validator->hasErrors()) {
            $inputsOld = array_map(function ($campo) {
                Validator::sanitizeInput($campo);
                return $campo;
            }, ['name' => $data['name'], 'email' => $data['email']]);
            return redirect()->route('register')->with(['error' => $validator->getErrorHandler(), 'old' => $inputsOld]);
        } else {
            /**Si no hay errores en los datos enviara una consulta para la creacion del usuario,
             * esta funcion se encargara de verificar los datos en la base de datos y crear el usuario
             */
            $result = User::createUser($data);

            /**Si la consulta se ha ejecutado con exito no llevara a la ruta de login, en el caso
             * de algun error se devolvera informacion al usuario
             */
            if ($result['result']) {
                return redirect()->route('login');
            } else {
                return redirect()->route('register')->with('errorDB', $result['message']);
            }
        }
    }
    /** Funcion que nos retornara false si la contraseña enviada coincide con la de la base de datos*/
    private static function validateCredentials($credentials)
    {
        return Hash::check($credentials['password'], User::credentials($credentials));
    }

    /**Funcion para eliminar la sesión del usuario */
    public static function logOutSession()
    {
        SessionManager::startSession();
        unset($_SESSION['user_id']);
        return redirect()->route('login');
    }
}
