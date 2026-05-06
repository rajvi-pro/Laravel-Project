<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DoctorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        return $next($request);
    }
}
