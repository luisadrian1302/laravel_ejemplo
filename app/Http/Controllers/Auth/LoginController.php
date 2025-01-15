<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
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
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
{
    // Verificar el rol del usuario

    var_dump($user->rol);
    if ($user->rol !== 'superadmin') { // Cambia 'admin' por el rol que necesites
        Auth::logout(); // Cerrar sesión

        // Redirecciona con un mensaje de error
        return redirect('/login')->withErrors(['message' => 'No tienes permisos para acceder.']);
    }

    // Si el rol es correcto, sigue con el flujo normal
    return redirect()->intended($this->redirectPath());
}

}
