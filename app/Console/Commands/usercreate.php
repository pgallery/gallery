<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use App\Models\Roles;

class usercreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'usercreate {--name=} {--email=} {--password=} {--role=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание пользователя';
    
    protected $user;
    
    protected $roles;
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(User $user, Roles $roles)
    {
        parent::__construct();
        
        $this->user    = $user;
        $this->roles   = $roles;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if(empty($this->option('name')) 
            or empty($this->option('email')) 
            or empty($this->option('password')))
        {
            exit;
        }
        
        $output['name']     = $this->option('name');
        $output['email']    = $this->option('email');
        $output['password'] = $this->option('password');
        
        if($this->option('role'))
            $output['roles'][] = $this->roles->select('id')->where('name', $this->option('role'))->first()->id;
        
        $this->user->createWithRoles($output);
    }
}
