<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginController extends Controller
{
  public function show(): View
  {
    return view('auth.login');
  }

  public function login(LoginRequest $request): RedirectResponse
  {
    $demoUser = config('demo.user');

    if (
      $request->input('email') !== $demoUser['email']
      || $request->input('password') !== $demoUser['password']
    ) {
      return back()
        ->withErrors(['email' => 'بيانات الدخول غير صحيحة.'])
        ->withInput($request->only('email'));
    }

    $request->session()->put('auth.user', [
      'name' => $demoUser['name'],
      'email' => $demoUser['email'],
    ]);

    return redirect()->route('dashboard');
  }

  public function logout(Request $request): RedirectResponse
  {
    $request->session()->forget('auth.user');
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
  }
}
