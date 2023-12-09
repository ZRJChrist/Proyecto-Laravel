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
        $this->existsUser = SessionManager::read('user') !== false ?  true : false;
    }
    /*
    Funcion que se ejecuta al solicitar la url de login
    */
    public function getSignIn()
    {
        /* Redirige a la lista de tareas si es que existe una sesión*/
        if ($this->existsUser) {
            return redirect()->route('readTasks');
        } else {
            if (isset($_COOKIE['remember'])) {
                $rememberToken = $_COOKIE['remember'];
                $email = User::searchToken($rememberToken)['email'];
                SessionManager::write('user', User::getIdAndRole($email));
            }
            return view('content.login');
        }
    }
    /**
     * Funcion que recibira los campos post enviados del formulario login.
     * @param Request $request. Valores de los inputs
     */
    public  function attemptSignIn(Request $request)
    {
        //Excluimos el campo _token debido a que no utilizara en esta caso
        $request = $request->only('email', 'password', 'remember');
        $validator = new Validator();
        $validator->validateEmail($request['email']);
        if (!$validator->hasErrors()) {
            return redirect()->route('login')->with(['error' => $validator->getErrorHandler()]);
        }
        /*Verificamos si el email se encuentra en la base de datos, sino se encuentra
        nos devolvera un error*/

        if (!User::checkIfExistsEmail($request['email'])) {
            $validator->getErrorHandler()->addError('email', 'Email no registrado');
            return redirect()->route('login')->with(['error' => $validator->getErrorHandler()]);
        }
        /*Verificamos si los datos coinciden con los de la base de datos, en caso si coincidan 
        nos llevara a la pagina de listar tareas*/
        if (self::validateCredentials($request)) {
            // * Creacion de sesion del usuario
            SessionManager::write('user', User::getIdAndRole($request['email']));
            // * Verificar si se marco la opcion de recordar usuario.
            if (isset($request['remember'])) {
                $id = SessionManager::read('user', 'id');
                $remember = bin2hex(random_bytes(32));
                $token = hash('sha256', $remember);
                setcookie("remember", $token, time() + 86400, "/");
                User::update($id, ['remember_token' => $token]);
            }
            return redirect()->route('readTasks');
        } else {
            /**En caso de que las credenciales no coincidan nos devolvera informacion */
            $validator->getErrorHandler()->addError('password', 'Contraseña incorrecta');
            return redirect()->route('login')->with(['errorPass' => $validator->getErrorHandler()]);
        }
    }

    /** Funcion que retorna false si la contraseña enviada no coincide con la de la base de datos*/
    private static function validateCredentials($credentials)
    {
        return Hash::check($credentials['password'], User::credentials($credentials));
    }

    /**Funcion para eliminar la sesión del usuario */
    public static function logOutSession()
    {
        SessionManager::_closeSession();
        return redirect()->route('login');
    }
}
