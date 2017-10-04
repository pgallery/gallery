<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;

class password extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password {user} {passwd}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Изменение пароля пользователя';

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
        User::where('email', $this->argument('user'))->update([
            'password' => \Hash::make($this->argument('passwd')),
        ]);
    }
}
