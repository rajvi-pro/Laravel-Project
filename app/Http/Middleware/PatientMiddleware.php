<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PatientMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('patient_logged_in')) {
            return redirect()->route('patient.login');
        }

        return $next($request);
    }
}
