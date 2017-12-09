<?php

namespace Vmorozov\LaravelAdminGenerator\App\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider;
use Vmorozov\LaravelAdminGenerator\App\Controllers\Controller;
use Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager;

class AdminAuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
    * Where to redirect users after login.
    *
    */
    public function redirectTo(): string
    {
        return UrlManager::dashboardRoute();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view(AdminGeneratorServiceProvider::VIEWS_NAME.'::auth.login');
    }
}
