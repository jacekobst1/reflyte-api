<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class HorizonFlush extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'horizon:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear failed_jobs db table and flush redis queue';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->call('queue:flush');
        Redis::command('flushdb');
    }
}
