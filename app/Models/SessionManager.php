<?php

namespace App\Models;

/**
 * Autor: @ZRJChrist
 *
 * Descripción: Maneja la manipulación de sesiones en PHP. 
 * Proporciona métodos para iniciar la sesión, escribir y leer valores en la sesión, 
 * verificar la edad de la sesión y cerrar la sesión.
 *  
 * Fecha de creación: 23/11/2023
 * 
 */
class SessionManager
{
    // Duración máxima de la sesión en segundos
    protected static $SESSION_AGE = 1800;

    // Método para iniciar la sesión
    public static function startSession()
    {
        // Verifica si la sesión ya está iniciada; si no lo está, la inicia.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    // Método para escribir un valor en la sesión
    public static function write($key, $value)
    {
        // Verifica si la clave es una cadena; si no lo es, devuelve un mensaje de error.
        if (!is_string($key))
            return ['result' => false, 'message' => 'Value of key ' . $key . ' is not a string'];

        // Inicia la sesión
        self::startSession();

        // Asigna el valor a la clave en la sesión.
        $_SESSION[$key] = $value;
    }

    // Método para leer un valor de la sesión
    public static function read($key, $child = false)
    {
        // Verifica si la clave es una cadena; si no lo es, devuelve un mensaje de error.
        if (!is_string($key))
            return ['result' => false, 'message' => 'Value of key ' . $key . ' is not a string'];

        // Inicia la sesión
        self::startSession();

        // Verifica si la clave está definida y no es nula
        if (isset($_SESSION[$key]) && null !== $_SESSION[$key]) {
            // Verifica la edad de la sesión
            if (self::_age()) {
                if ($child == false) {
                    // Devuelve el valor asociado a la clave si no se especifica un hijo (child)
                    return $_SESSION[$key];
                } else {
                    // Si se especifica un hijo (child), verifica si está definido y lo devuelve
                    if (isset($_SESSION[$key][$child])) {
                        return $_SESSION[$key][$child];
                    }
                }
            }
        }
        // Retorna falso si no se cumple alguna de las condiciones anteriores
        return false;
    }

    // Método privado para verificar la edad de la sesión
    private static function _age()
    {
        // Obtiene el tiempo de la última actividad en la sesión
        $last = isset($_SESSION['LAST_ACTIVE']) ? $_SESSION['LAST_ACTIVE'] : false;

        // Verifica si la última actividad ocurrió hace más de SESSION_AGE segundos
        if (false !== $last && (time() - $last > self::$SESSION_AGE)) {
            // Cierra la sesión si ha pasado más tiempo del permitido
            self::_closeSession();
            return false;
        }
        // Actualiza el tiempo de la última actividad en la sesión
        $_SESSION['LAST_ACTIVE'] = time();
        return true;
    }
    // Método para cerrar la sesión
    public static function _closeSession()
    {
        setcookie("remember", "", time() - 86400, "/");
        self::startSession();
        // Desvincula todas las variables de sesión
        session_unset();
        // Destruye la sesión
        session_destroy();
    }
}
