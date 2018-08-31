<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use Carbon\Carbon;

use App\USER;
use App\ELECTION;
use App\VOTE;
use App\CANDIDATE;

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
    $election = ELECTION::where('election_end', '>', Carbon::now()->subMonth())->with('election_start', '<', Carbon::now()->addDay());
    return view('pages.home')->with('elections', isset($election)?$election:null);
})->name('home');

Route::post('auth', function (Request $request) {
    $ticket = $request["ticket"];

    if(USER::where('ticket', $ticket)->where('expire', '>', Carbon::now())->exists()) {
        return redirect()->route('home')->withCookie(cookie('ticketdata', Crypt::encryptString($ticket), 60*60));
    }
    else {
        return redirect()->route('home')->with('errorcode', 7001);
    }
})->name('auth');

Route::get('logout', function() {
    return redirect()->route('home')->withCookie(Cookie::forget('ticketdata'));
})->name('logout');

Route::prefix('user')->middleware(['checkauth'])->group(function () {
    Route::get('elect', function() {
        $election = ELECTION::where('election_end', '>', Carbon::now()->subMonth())->with('election_start', '<', Carbon::now()->addDay());
        return view('pages.election')->with('elections', isset($election)?$election:null);
    })->name('user.elect');
    Route::get('vote/{elect}', function($elect) {
        if(empty($elect) || !(ELECTION::where('election_id', $elect)->exists())) {
            return view('pages.error.notfound');
        }
        $election = ELECTION::where('election_id', $elect)->first();
        $candidates = CANDIDATE::where('election_id', $elect)->get();
        return view('pages.vote')->with('election', $election)->with('candidates', $candidates);
    })->name('user.vote.page');
    Route::post('vote', function(Request $request) {
        if(empty($request["candidate_id"]) || empty($request["election_id"])) {
            return view('page.error.custom')->with('error_text', 'Unexpected Error, please try again');
        }
        if(!(CANDIDATE::where('candidate_id', $request["candidate_id"])->where('election_id', $request["election_id"])->exists())) {
            return view('page.error.custom')->with('error_text', 'This candidate does not exist');
        }

        $ticket_id = Crypt::decryptString(Cookie::get('ticketdata'));

        if(VOTE::where('ticket_id', $ticket_id)->where('election_id', $request["election_id"])->exists()) {
            return view('page.error.custom')->with('error_text', 'You already voted!');
        }

        $last_vote = VOTE::select('vote_id')->all()->last();

        $vote = new VOTE;
        $vote->vote_id = str_random(256);
        $vote->ticket_id = $ticket_id;
        $vote->election_id = $request["election_id"];
        $vote->candidate_id = $request["candidate_id"];
        $vote->prev_id = $last_vote["vote_id"];
        $vote->save();

        return view('page.voted');

    })->name('user.vote.sys');
});

Route::prefix('admin')->middleware(['checkauth', 'checkadmin'])->group(function () {
    Route::get('/', function() {
        return view('pages.admin.home');
    })->name('admin.home');
    Route::get('addelect', function() {
        return view('pages.admin.addelect');
    })->name('admin.elect.add.page');
    Route::get('addcandidate/{elect}', function($elect) {
        return view('page.admin.addcandidate');
    })->name('admin.candidate.add.page');
    Route::post('elect', function(Request $request) {
        //ADD ELECT FUNCTION
    })->name('admin.elect.add');
    Route::post('candidate', function(Request $request) {
        //ADD candidate FUNCTION
    })->name('admin.candidate.add');
    Route::get('elects', function() {
        //SHOW ALL ELECT
    })->name('admin.elect.all');
    Route::get('elect/{elect}', function($elect) {
        //SHOW ELECT DETAIL
    })->name('admin.elect.show');
    Route::get('elect/edit/{elect}', function($elect) {
        //SHOW EDIT
    })->name('admin.elect.edit');
    Route::get('candidate/{elect}/{candidate}', function($elect, $candidate) {
        //SHOW candidate DETAIL
    })->name('admin.candidate.show');
    Route::get('candidate/edit/{elect}/{candidate}', function($elect, $candidate) {
        //SHOW EDIT
    })->name('admin.candidate.edit');
    Route::delete('elect', function (Request $request) {
        //DELETE ELECT
    })->name('admin.elect.delete');
    Route::delete('candidate', function (Request $request) {
       //DELETE candidate 
    })->name('admin.candidate.delete');
});

Route::fallback(function () {
    return view('pages.error.notfound');
});