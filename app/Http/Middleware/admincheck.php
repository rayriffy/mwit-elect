<?php

namespace App\Http\Middleware;

use Closure;

use Cookie;
use Illuminate\Support\Facades\Crypt;

class admincheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ticket = \App\USER::select('is_admin')->where('ticket', Crypt::decryptString(Cookie::get('ticketdata')))->first();
        if ($ticket["is_admin"] == true) {
            return $next($request);
        }
        else {
            return redirect()->route('home');
        }
    }
}
