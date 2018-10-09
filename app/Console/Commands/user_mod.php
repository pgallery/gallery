<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;

class user_mod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:mod {user} {--email=} {--password=} {--google2fa=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Редактирование пользователя';

    protected $user;    
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        parent::__construct();
        
        $this->user    = $user;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
                
        if(empty($this->option('email')) 
            and empty($this->option('password')) 
            and empty($this->option('google2fa')))
        {
            exit;
        }
        
        if($this->option('email'))
            $output['email'] = $this->option('email');
        
        if($this->option('password')) 
            $output['password'] = \Hash::make($this->option('password'));

        if($this->option('google2fa'))
            $output['google2fa_enabled'] = (($this->option('google2fa') == 'enabled') ? true : false);
        
        $this->user->where('email', $this->argument('user'))->update($output);
        
    }
}
