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
    $election = ELECTION::where('election_end', '>', Carbon::now()->subMonth())->where('election_start', '<', Carbon::now()->addDay())->get();
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
        $election = ELECTION::where('election_end', '>', Carbon::now()->subMonth())->where('election_start', '<', Carbon::now()->addDay())->get();
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
            return view('pages.error.custom')->with('error_text', 'Unexpected Error, please try again');
        }
        if(!(CANDIDATE::where('candidate_id', $request["candidate_id"])->where('election_id', $request["election_id"])->exists())) {
            return view('pages.error.custom')->with('error_text', 'This candidate does not exist');
        }

        $ticket_id = Crypt::decryptString(Cookie::get('ticketdata'));

        if(VOTE::where('ticket_id', $ticket_id)->where('election_id', $request["election_id"])->exists()) {
            return view('pages.error.custom')->with('error_text', 'You already voted!');
        }

        $last_vote = VOTE::select('vote_id')->orderBy('updated_at', 'desc')->first();

        $vote = new VOTE;
        $vote->vote_id = str_random(256);
        $vote->ticket_id = $ticket_id;
        $vote->election_id = $request["election_id"];
        $vote->candidate_id = $request["candidate_id"];
        $vote->prev_id = isset($last_vote["vote_id"])?$last_vote["vote_id"]:"start";
        $vote->save();

        return view('pages.voted');

    })->name('user.vote.sys');
});

Route::prefix('admin')->middleware(['checkauth', 'checkadmin'])->group(function () {
    Route::get('/', function() {
        $elections = ELECTION::where('admin_ticket', Crypt::decryptString(Cookie::get('ticketdata')))->limit(5)->get();
        return view('pages.admin.home')->with('elections', $elections);
    })->name('admin.home');
    Route::get('addelect', function() {
        return view('pages.admin.add.election');
    })->name('admin.elect.add.page');
    Route::get('addcandidate/{elect}', function($elect) {
        return view('page.admin.add.candidate');
    })->name('admin.candidate.add.page');
    Route::post('elect', function(Request $request) {
        if(strtotime($request["election_start"]) === false) {
            return view('pages.error.custom')->with('error_text', 'Election Start field is in invalid format');
        }
        if(strtotime($request["election_end"]) === false) {
            return view('pages.error.custom')->with('error_text', 'Election End field is in invalid format');
        }
        if(strtotime($request["election_end"]) - strtotime($request["election_start"]) <= 0) {
            return view('pages.error.custom')->with('error_text', 'Election Start is greater than Election End');
        }
        $request["election_start"] = Carbon::createFromTimeString($request["election_start"], 'Asia/Bangkok')->setTimezone('UTC');
        $request["election_end"] = Carbon::createFromTimeString($request["election_end"], 'Asia/Bangkok')->setTimezone('UTC');

        $elect_id = str_random(64);
        $election = new App\ELECTION;
        $election->election_id = $elect_id;
        $election->election_name = $request["election_name"];
        $election->election_start = $request["election_start"];
        $election->election_end = $request["election_end"];
        $election->admin_ticket = Crypt::decryptString(Cookie::get('ticketdata'));
        $election->save();

        return redirect()->route('admin.elect.edit.page', ['elect' => $elect_id])->with('statusmsg', 'Election created successfully!'); 
    })->name('admin.elect.add');
    Route::post('candidate', function(Request $request) {
        if(empty($request["election_id"]) || empty($request["candidate_name"])) {
            return view('pages.error.notfound');
        }
        if(!(ELECTION::where('election_id', $request["election_id"])->where('admin_ticket', Crypt::decryptString(Cookie::get('ticketdata')))->exists())) {
            return view('pages.error.notfound');
        }

        $candidate_id = str_random(64);

        $candidate = new App\CANDIDATE;
        $candidate->candidate_id = $candidate_id;
        $candidate->election_id = $request["election_id"];
        $candidate->candidate_name = $request["candidate_name"];
        $candidate->save();

        return redirect()->route('admin.elect.edit.page', ['elect' => $request["election_id"]])->with('statusmsg', 'Added successfully!'); 
    })->name('admin.candidate.add');
    Route::get('elects', function() {
        $elections = ELECTION::where('admin_ticket', Crypt::decryptString(Cookie::get('ticketdata')))->get();
        return view('pages.admin.elects')->with('elections', $elections);
    })->name('admin.elect.all');
    Route::get('elect/{elect}', function($elect) {
        if(empty($elect)) {
            return view('pages.error.notfound');
        }
        if(!(ELECTION::where('election_id', $elect)->where('admin_ticket', Crypt::decryptString(Cookie::get('ticketdata')))->exists())) {
            return view('pages.error.notfound');
        }
        $election = ELECTION::where('election_id', $elect)->where('admin_ticket', Crypt::decryptString(Cookie::get('ticketdata')))->first();
        $candidates = CANDIDATE::where('election_id', $elect)->get();
        $votes = VOTE::where('election_id', $elect)->get();
        return view('pages.admin.view.election')->with('election', $election)->with('candidates', isset($candidates)?$candidates:null)->with('votes', isset($votes)?$votes:null);
    })->name('admin.elect.show');
    Route::get('elect/edit/{elect}', function($elect) {
        if(empty($elect)) {
            return view('pages.error.notfound');
        }
        if(!(ELECTION::where('election_id', $elect)->where('admin_ticket', Crypt::decryptString(Cookie::get('ticketdata')))->exists())) {
            return view('pages.error.notfound');
        }
        $election = ELECTION::where('election_id', $elect)->where('admin_ticket', Crypt::decryptString(Cookie::get('ticketdata')))->first();
        $candidates = CANDIDATE::where('election_id', $elect)->get();
        return view('pages.admin.edit.election')->with('election', $election)->with('candidates', isset($candidates)?$candidates:null);
    })->name('admin.elect.edit.page');
    Route::put('elect/edit/{elect}', function(Request $request, $elect) {
        if(empty($elect)) {
            return view('pages.error.notfound');
        }
        if(!(ELECTION::where('election_id', $elect)->where('admin_ticket', Crypt::decryptString(Cookie::get('ticketdata')))->exists())) {
            return view('pages.error.notfound');
        }
        if(strtotime($request["election_start"]) === false) {
            return view('pages.error.custom')->with('error_text', 'Election Start field is in invalid format');
        }
        if(strtotime($request["election_end"]) === false) {
            return view('pages.error.custom')->with('error_text', 'Election End field is in invalid format');
        }
        if(strtotime($request["election_end"]) - strtotime($request["election_start"]) <= 0) {
            return view('pages.error.custom')->with('error_text', 'Election Start is greater than Election End');
        }
        $request["election_start"] = Carbon::createFromTimeString($request["election_start"], 'Asia/Bangkok')->setTimezone('UTC');
        $request["election_end"] = Carbon::createFromTimeString($request["election_end"], 'Asia/Bangkok')->setTimezone('UTC');
        if(ELECTION::where('election_id', $elect)->update($request->except(['_token', '_method']))) {
            return redirect()->route('admin.elect.edit.page', ['elect' => $elect])->with('statusmsg', 'Updated successfully!'); 
        }
        else {
            return view('pages.error.custom')->with('error_text', 'Unexpected error has occurred :(');
        }
    })->name('admin.elect.edit.sys');
    Route::put('candidate/edit/{elect}/{candidate}', function(Request $request, $elect, $candidate) {
        if(empty($elect) || empty($candidate)) {
            return view('pages.error.notfound');
        }
        if(!(ELECTION::where('election_id', $elect)->where('admin_ticket', Crypt::decryptString(Cookie::get('ticketdata')))->exists())) {
            return view('pages.error.notfound');
        }
        if(!(CANDIDATE::where('election_id', $elect)->where('candidate_id', $candidate)->exists())) {
            return view('pages.error.notfound');
        }
        if(CANDIDATE::where('election_id', $elect)->where('candidate_id', $candidate)->update($request->except(['_token', '_method']))) {
            return redirect()->route('admin.elect.edit.page', ['elect' => $elect])->with('statusmsg', 'Update requested candidate successfully!'); 
        }
        else {
            return view('pages.error.custom')->with('error_text', 'Unexpected error has occurred :(');
        }
    })->name('admin.candidate.edit.sys');
    Route::delete('elect/delete/{elect}', function ($elect) {
        if(empty($elect)) {
            return view('pages.error.notfound');
        }
        if(!(ELECTION::where('election_id', $elect)->where('admin_ticket', Crypt::decryptString(Cookie::get('ticketdata')))->exists())) {
            return view('pages.error.notfound');
        }
        $res = ELECTION::where('election_id', $elect)->delete();
        $res2 = CANDIDATE::where('election_id', $elect)->delete();
        $res3 = VOTE::where('election_id', $elect)->delete();
        return view('pages.admin.deleted.election');
    })->name('admin.elect.delete');
    Route::delete('candidate/delete/{elect}/{candidate}', function ($elect, $candidate) {
        if(empty($elect) || empty($candidate)) {
            return view('pages.error.notfound');
        }
        if(!(ELECTION::where('election_id', $elect)->where('admin_ticket', Crypt::decryptString(Cookie::get('ticketdata')))->exists())) {
            return view('pages.error.notfound');
        }
        if(!(CANDIDATE::where('election_id', $elect)->where('candidate_id', $candidate)->exists())) {
            return view('pages.error.notfound');
        }
        $res = CANDIDATE::where('election_id', $elect)->where('candidate_id', $candidate)->delete();
        return redirect()->route('admin.elect.edit.page', ['elect' => $elect])->with('statusmsg', 'Remove requested candidate successfully!'); 
    })->name('admin.candidate.delete');

    //TODO GENERATE TICKET
});

Route::fallback(function () {
    return view('pages.error.notfound');
});