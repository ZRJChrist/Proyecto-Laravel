<?php

namespace App\Http\Controllers;

use App\Models\SessionManager;

class HomeController
{
    public function getHome()
    {
        SessionManager::startSession();
        if (SessionManager::read('user_id')) {
            return view('content.home');
        }
        return view('content.home');
    }
}
