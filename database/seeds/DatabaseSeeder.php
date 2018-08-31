<?php

use Illuminate\Database\Seeder;

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
        $user->save();

        $user = new App\USER;
        $user->ticket = str_random(8);
        $user->is_admin = false;
        $user->save();
        // $this->call(UsersTableSeeder::class);
    }
}
