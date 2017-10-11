<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;

class usermod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'usermod  {user} {--email=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Редактирование пользователя';

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
        echo $this->argument('user');
        echo "\n\n";
        
        
        if(empty($this->option('email')) and empty($this->option('password')))
            exit;
        
        if($this->option('email'))
            $output['email'] = $this->option('email');
        
        if($this->option('password')) 
            $output['password'] = \Hash::make($this->option('password'));
        
        User::where('email', $this->argument('user'))->update($output);
        
    }
}
