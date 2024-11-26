<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdminLevel
{
        public function handle($request, Closure $next, $requiredLevel = 0)
        {
            $user = Auth::user();

            if ($user && $user->is_admin == $requiredLevel) {
                return $next($request);
            }

            return redirect()->back();
        }
    }
