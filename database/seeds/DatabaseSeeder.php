<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = new App\USER;
        $user->ticket = str_random(8);
        $user->is_admin = true;
        $user->expire = Carbon::now()->addYears(1);
        $user->save();

        $user = new App\USER;
        $user->ticket = str_random(8);
        $user->is_admin = false;
        $user->expire = Carbon::now()->addYears(1);
        $user->save();

        $test[0] = str_random(64);
        $election = new App\ELECTION;
        $election->election_id = $test[0];
        $election->election_name = "Test 1";
        $election->election_start = Carbon::now()->addHours(2);
        $election->election_end = Carbon::now()->addDay();
        $election->save();

        $test[1] = str_random(64);
        $election = new App\ELECTION;
        $election->election_id = $test[1];
        $election->election_name = "Test 2";
        $election->election_start = Carbon::now()->addDays(2);
        $election->election_end = Carbon::now()->addDay(5);
        $election->save();

        $test[2] = str_random(64);
        $election = new App\ELECTION;
        $election->election_id = $test[2];
        $election->election_name = "Test 3";
        $election->election_start = Carbon::now()->subHours(2);
        $election->election_end = Carbon::now()->addDay(1);
        $election->save();

        for($i = 0 ; $i < 3 ; $i++) {
            for($j = 0 ; $j < 5 ; $j++) {
                $candidate = new App\CANDIDATE;
                $candidate->candidate_id = str_random(128);
                $candidate->election_id = $test[$i];
                $candidate->candidate_name = "Guy " . $j;
                $candidate->save();
            }
        }
    }
}
