<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthenticated
{
  public function handle(Request $request, Closure $next): Response
  {
    if (! $request->session()->has('auth.user')) {
      if ($request->expectsJson() || $request->is('api/*')) {
        return response()->json([
          'success' => false,
          'message' => 'غير مصرح. يلزم تسجيل الدخول.',
        ]);
      }

      return redirect()->route('login');
    }

    return $next($request);
  }
}
