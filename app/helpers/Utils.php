<?php

namespace App\Helpers;

use App\Models\SessionManager;

/**
 * Helper class
 * Para no tener que estar importanto la clase, esta se esta utilizando
 * el atajo de aliases que proporciona laravel
 */
class Utils
{
    public static function isLogIn()
    {
        return SessionManager::read('user_id') !== false ?  true : false;
    }
}
