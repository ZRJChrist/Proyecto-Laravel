<?php

namespace App\Helpers;

use App\Models\SessionManager;
use App\Models\User;

/**
 * Helper class
 */
class Utils
{
    public static function isLogIn()
    {
        return SessionManager::read('user') !== false ?  true : false;
    }
    public static function getOperators()
    {
        return User::getAllOperarios();
    }
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

    public static function getNametoNav()
    {
        $name =  User::getName(SessionManager::read('user', 'id'));
        return $name['name'];
    }

    public static function isAdmin()
    {
        return SessionManager::read('user', 'role') == 1 ? true : false;
    }
    public static function isUserAuthorized($idTask)
    {
        $userId = SessionManager::read('user', 'id');
        $userRole = SessionManager::read('user', 'role');
        return ($userId == $idTask || $userRole == 1) ? true : false;
    }
}
