<?php

namespace App\Helpers;

use App\Models\SessionManager;

class Utils
{
    public static function isLogIn()
    {
        return SessionManager::read('user_id') !== false ?  true : false;
    }
}
