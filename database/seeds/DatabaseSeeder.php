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
        $admin_ticket = str_random(8);
        $user = new App\USER;
        $user->ticket = $admin_ticket;
        $user->is_admin = true;
        $user->expire = Carbon::now()->addYears(1);
        $user->save();

        $user_ticket = str_random(8);
        $user = new App\USER;
        $user->ticket = $user_ticket;
        $user->is_admin = false;
        $user->expire = Carbon::now()->addYears(1);
        $user->save();

        $elect[0] = str_random(64);
        $election = new App\ELECTION;
        $election->election_id = $elect[0];
        $election->election_name = "Test 1";
        $election->election_start = Carbon::now()->addHours(2);
        $election->election_end = Carbon::now()->addDay();
        $election->admin_ticket = $admin_ticket;
        $election->save();

        $elect[1] = str_random(64);
        $election = new App\ELECTION;
        $election->election_id = $elect[1];
        $election->election_name = "Test 2";
        $election->election_start = Carbon::now()->addDays(2);
        $election->election_end = Carbon::now()->addDay(5);
        $election->admin_ticket = $admin_ticket;
        $election->save();

        $elect[2] = str_random(64);
        $election = new App\ELECTION;
        $election->election_id = $elect[2];
        $election->election_name = "Test 3";
        $election->election_start = Carbon::now()->subHours(2);
        $election->election_end = Carbon::now()->addDay(1);
        $election->admin_ticket = $admin_ticket;
        $election->save();

        for($i = 0 ; $i < 3 ; $i++) {
            for($j = 0 ; $j < 5 ; $j++) {
                $candidate = new App\CANDIDATE;
                $candidate->candidate_id = str_random(128);
                $candidate->election_id = $elect[$i];
                $candidate->candidate_name = "Guy " . $j;
                $candidate->save();
            }
        }
    }
}
