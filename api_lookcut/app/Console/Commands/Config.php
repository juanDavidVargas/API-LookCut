<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Config extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear app cache, swagger';

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
        Artisan::call('config:cache');
        Artisan::call('cache:clear');
        Artisan::call('l5-swagger:generate');
        Artisan::call('config:cache');
        Artisan::call('cache:clear');
        $this->info('Clear completed successfully');
    }
}
