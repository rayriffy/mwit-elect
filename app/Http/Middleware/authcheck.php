<?php

namespace App\Http\Middleware;

use Closure;

use Cookie;
use Illuminate\Support\Facades\Crypt;

class authcheck
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
        if(Cookie::get('ticketdata') !== null) {
            $ticket = Crypt::decryptString(Cookie::get('ticketdata'));
            if(\App\USER::where('ticket', $ticket)->exists()) {
                $ticket_exp = \App\USER::select('expire')->where('ticket', $ticket)->first();
                if(\Carbon\Carbon::parse($ticket_exp["expire"])->isFuture()) {
                    return $next($request);
                }
                else {
                    return redirect()->route('home')->with('errorcode', 7003)->withCookie(Cookie::forget('ticketdata'));
                }
            }
            else {
                return redirect()->route('home')->with('errorcode', 7003)->withCookie(Cookie::forget('ticketdata'));
            }
        }
        return redirect()->route('home')->with('errorcode', 7002)->withCookie(Cookie::forget('ticketdata'));
    }
}
