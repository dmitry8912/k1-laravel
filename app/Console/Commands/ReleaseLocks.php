<?php

namespace App\Console\Commands;

use App\Models\EndpointLocks;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
class ReleaseLocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'locks:release';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Releases all locks that not updated more than 5 minutes';

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
     * @return int
     */
    public function handle()
    {
        $locks = EndpointLocks::where('updated_at','<=',Carbon::now()->subMinutes(intval(config('k1c.lock_keepalive'))));
        $count = $locks->count();
        Log::info("found $count locks");
        $this->info("found $count locks");
        $locks->delete();
        return 0;
    }
}
