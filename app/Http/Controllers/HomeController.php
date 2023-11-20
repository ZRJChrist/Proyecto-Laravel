<?php

namespace App\Http\Controllers;

class HomeController
{
    public function getHome()
    {
        session_start();
        return view('content.home');
    }
}
