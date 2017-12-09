<?php

namespace Vmorozov\LaravelAdminGenerator\App\Controllers;

use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider;

class AdminHomeController extends Controller
{
    public function showDashboard()
    {
        return view(AdminGeneratorServiceProvider::VIEWS_NAME.'::dashboard.basic');
    }
}
