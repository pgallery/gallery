<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Settings;
use Setting;

class EnableQueues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Queues:Enable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Включает использование обработчика очередей';

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
        if(Setting::get('use_queue') == 'no')
            $setting = Settings::where('set_name', 'use_queue')->update([
                'set_value'   => 'yes',
            ]);
    }
}
