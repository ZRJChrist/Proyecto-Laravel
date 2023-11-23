<?php

namespace App\Http\Controllers;

use App\Models\SessionManager;

class HomeController
{
    public function getHome()
    {
        SessionManager::startSession();
        return view('content.home');
    }
}
