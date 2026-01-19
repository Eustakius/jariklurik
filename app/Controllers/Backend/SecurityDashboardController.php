<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;

class SecurityDashboardController extends BaseController
{
    public function index()
    {
        return view('Backend/Security/dashboard');
    }
}
