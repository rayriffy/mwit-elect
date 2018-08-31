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
    }
}
