<?php

namespace App\Models;

class SessionManager
{
    protected static $SESSION_AGE = 1800;
    // Método para iniciar la sesión
    public static function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function write($key, $value)
    {
        if (!is_string($key))
            return ['result' => false, 'message' => 'Value of key ' . $key . ' is not a string'];
        self::startSession();
        $_SESSION[$key] = $value;
    }

    // Método para obtener un valor de la sesión
    public static function read($key, $child = false)
    {
        if (!is_string($key))
            return ['result' => false, 'message' => 'Value of key ' . $key . ' is not a string'];
        self::startSession();
        if (isset($_SESSION[$key])) {
            self::_age();
            if ($child == false) {
                return $_SESSION[$key];
            } else {

                if (isset($_SESSION[$key][$child])) {
                    return $_SESSION[$key][$child];
                }
            }
        }
        return false;
    }
    private static function _age()
    {
        $last = isset($_SESSION['LAST_ACTIVE']) ? $_SESSION['LAST_ACTIVE'] : false;
        if (false !== $last && (time() - $last > self::$SESSION_AGE))
            self::_closeSession();
        $_SESSION['LAST_ACTIVE'] = time();
    }
    // Método para cerrar la sesión
    private static function _closeSession()
    {
        self::startSession();
        session_unset();
        session_destroy();
    }
}
