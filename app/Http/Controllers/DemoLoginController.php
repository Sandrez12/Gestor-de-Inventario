<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

/** Acceso con un clic al usuario de demostración (solo si DEMO_MODE=true). */
class DemoLoginController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        $usuario = User::where('email', 'demo@gestor.test')->firstOrFail();

        Auth::login($usuario);
        request()->session()->regenerate();

        return redirect()->route('dashboard');
    }
}
