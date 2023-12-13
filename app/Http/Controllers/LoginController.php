<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\SessionManager;
use App\Models\Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\Utils;

/**
 * Autor: @ZRJChrist
 *
 * Descripción: Controlador para la autenticación de usuarios.
 *   
 * Fecha de creación: 23/11/2023
 */
class LoginController
{
    // Indica si hay un usuario autenticado actualmente.
    private $existsUser;

    public function __construct()
    {
        // Verifica si hay una sesión iniciada y asigna el valor a $existsUser.
        $this->existsUser = SessionManager::read('user') !== false ?  true : false;
    }

    /**
     * Método que se ejecuta al solicitar la URL de inicio de sesión. 
     */
    public function getSignIn()
    {
        if ($this->existsUser) {
            // Redirige a la lista de tareas si hay una sesión.
            if (Utils::isAdmin()) {
                return redirect()->route('readTasks');
            } else {
                return redirect()->route('readTasks', ['operario' => SessionManager::read('user', 'role')]);
            }
        } elseif (isset($_COOKIE['remember']) && $_COOKIE['remember'] != '') {
            // Recuperar el token de recordatorio y establecer la sesión.
            $rememberToken = $_COOKIE['remember'];
            $email = User::searchToken($rememberToken)['email'];
            SessionManager::write('user', User::getIdAndRole($email));
            if (Utils::isAdmin()) {
                return redirect()->route('readTasks');
            } else {
                return redirect()->route('readTasks', ['operario' => SessionManager::read('user', 'role')]);
            }
        } else {
            // Muestra la vista de inicio de sesión si no hay sesión ni token de recordatorio.
            return view('content.login');
        }
    }
    /**
     * Método que recibe los campos POST enviados del formulario de inicio de sesión.
     * @param Request $request. Valores de los inputs.
     */
    public  function attemptSignIn(Request $request)
    {
        // Excluir el campo _token ya que no se utilizará en este caso.
        $request = $request->only('email', 'password', 'remember');
        $validator = new Validator();
        $validator->validateEmail($request['email']);

        // Validar el formato del correo electrónico.
        if (!$validator->hasErrors()) {
            return redirect()->route('login')->with(['error' => $validator->getErrorHandler()]);
        }

        // Verificar si el correo electrónico está registrado en la base de datos.
        if (!User::checkIfExistsEmail($request['email'])) {
            $validator->getErrorHandler()->addError('email', 'Email no registrado');
            return redirect()->route('login')->with(['error' => $validator->getErrorHandler()]);
        }
        // Verificar si las credenciales coinciden con las de la base de datos.
        if (self::validateCredentials($request)) {
            // Creación de sesión de usuario.
            SessionManager::write('user', User::getIdAndRole($request['email']));
            // Verificar si se marcó la opción de recordar usuario.
            if (isset($request['remember'])) {
                $id = SessionManager::read('user', 'id');
                $remember = bin2hex(random_bytes(32));
                $token = hash('sha256', $remember);
                setcookie("remember", $token, time() + 86400, "/");
                User::update($id, ['remember_token' => $token]);
            }
            if (Utils::isAdmin()) {
                return redirect()->route('readTasks');
            } else {
                return redirect()->route('readTasks', ['page' => 1, 'operario' => SessionManager::read('user', 'id')]);
            }
        } else {
            // En caso de que las credenciales no coincidan se informara al usuario
            $validator->getErrorHandler()->addError('password', 'Contraseña incorrecta');
            return redirect()->route('login')->with(['errorPass' => $validator->getErrorHandler()]);
        }
    }

    /**
     * Metodo que retorna false si la contraseña enviada no coincide con la de la base de datos.
     *  */
    private static function validateCredentials($credentials)
    {
        return Hash::check($credentials['password'], User::credentials($credentials));
    }

    /**  
     *Método para cerrar la sesión del usuario.
     * */
    public static function logOutSession()
    {
        SessionManager::_closeSession();
        return redirect()->route('login');
    }
}
