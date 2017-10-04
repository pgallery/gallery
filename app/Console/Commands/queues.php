<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Settings;

class queues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queues {status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Включение и отключение использования обработчика очередей';

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
        Settings::where('set_name', 'use_queue')->update([
            'set_value'   => (($this->argument('status') == 'enabled') ? 'yes' : 'no'),
        ]);
    }
}
