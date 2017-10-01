<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;

class go2fa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'go2fa {user} {status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Включение и отключение двухфакторной авторизации';

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
            'google2fa_enabled' => (($this->argument('status') == 'enable') ? true : false),
        ]);
    }
}
