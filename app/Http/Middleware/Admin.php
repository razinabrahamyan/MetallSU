<?php

namespace App\Http\Middleware;

use App\Classes\Constants\Role;
use Closure;
use Illuminate\Http\Request;

class Admin {
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        if(auth()->user()->role_id == Role::ADMIN){
            return $next($request);
        }
        session()->flash('success', 'Недостаточно прав');
        return redirect()->route('index');
    }
}
