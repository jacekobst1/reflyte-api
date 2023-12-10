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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->call('queue:flush');

        Redis::connection()->del([config('horizon.prefix') . 'failed:*']);
        $this->info('each individual failed job flushed');

        Redis::connection()->del([config('horizon.prefix') . 'failed_jobs']);
        $this->info('failed_jobs flushed');

        Redis::command('flushdb');
        $this->info('reddis flushed');
    }
}
