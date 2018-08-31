<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use App\USER;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::post('/auth', function (Request $request) {
    $ticket = $request["ticket"];

    if(USER::where('ticket', $ticket)->exists()) {
        return redirect()->route('home')->withCookie(cookie('ticketdata', Crypt::encryptString($ticket), 60*60));
    }
    else {
        return redirect()->route('home')->with('errorcode', 7001);
    }

    
});