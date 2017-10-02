<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Archives;

class ArchivesCleanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 5;
    
    protected $archive;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($archive)
    {
        $this->$archive = $archive;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Archives::destroyWithZipper($this->$archive);
    }
}
