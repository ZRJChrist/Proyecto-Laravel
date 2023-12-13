<?php



namespace App\Helpers;

use App\Models\SessionManager;
use App\Models\User;

/**
 * Autor: @ZRJChrist
 *
 * Descripción: Proporciona funciones de utilidad relacionadas con la gestión de sesiones de usuario, 
 * la obtención de datos de usuarios y la autorización de usuarios para realizar ciertas acciones. 
 *   
 * Fecha de creación: 23/11/2023

 */
class Utils
{
    /**
     * Verifica si hay un usuario logueado.
     *
     * @return bool Retorna verdadero si hay un usuario en sesión, de lo contrario, retorna falso.
     */
    public static function isLogIn()
    {
        return SessionManager::read('user') !== false ?  true : false;
    }
    /**
     * Obtiene todos los operarios de la base de datos.
     *
     * @return mixed Retorna los operarios almacenados en la base de datos.
     */
    public static function getOperators()
    {
        return User::getAllOperarios();
    }
    /**
     * Construye parámetros para enlaces URL, incluyendo la página y otros parámetros opcionales.
     *
     * @param int $page Número de página.
     * @param mixed $params Parámetros adicionales opcionales.
     * @return array Retorna un array de parámetros para ser utilizado en enlaces URL.
     */
    public static function paramLinks(int $page, mixed $params = false)
    {
        $paramsToLink = ['page' => $page];
        if (false !== $params) {
            foreach ($params as $key => $value) {
                $paramsToLink[$key] = $value;
            }
        }
        return $paramsToLink;
    }

    /**
     * Obtiene el nombre del usuario en sesión para mostrar en la interfaz.
     *
     * @return string Retorna el nombre del usuario en sesión.
     */
    public static function getNametoNav()
    {
        $name =  User::getName(SessionManager::read('user', 'id'));
        return $name['name'];
    }
    /**
     * Verifica si el usuario en sesión tiene el rol de administrador.
     *
     * @return bool Retorna verdadero si el usuario en sesión tiene el rol de administrador, de lo contrario, retorna falso.
     */
    public static function isAdmin()
    {
        return SessionManager::read('user', 'role') == 1 ? true : false;
    }
    /**
     * Verifica si el usuario en sesión está autorizado para realizar acciones relacionadas con un operario específico.
     *
     * @param int $idOperario ID del operario a verificar.
     * @return bool Retorna verdadero si el usuario en sesión está autorizado, de lo contrario, retorna falso.
     */
    public static function isUserAuthorized($idOperario)
    {
        $userId = SessionManager::read('user', 'id');
        $userRole = SessionManager::read('user', 'role');
        return ($userId == $idOperario || $userRole == 1) ? true : false;
    }
}
