<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TicketAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:admin {amount : Amount of ticket to generate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Admin Ticket';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $amount = $this->argument('amount');
        if ($this->confirm('Do you want to generate '.$amount.' amount of Admin Token?')) {
            for ($i=0 ; $i<$amount ; $i++) {
                $admin_ticket = str_random(12);
                $user = new \App\USER;
                $user->ticket = $admin_ticket;
                $user->is_admin = true;
                $user->expire = \Carbon\Carbon::now()->addYears(1);
                $user->save();
                $this->info($admin_ticket);
            }
        }
    }
}
