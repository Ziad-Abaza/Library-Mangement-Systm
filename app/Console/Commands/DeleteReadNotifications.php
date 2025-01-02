<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeleteReadNotifications extends Command
{
    /*
    |--------------------------------------------------------------------------
    | Command Signature
    |--------------------------------------------------------------------------
    */
    protected $signature = 'notifications:clean';

    /*
    |--------------------------------------------------------------------------
    | Command Description
    |--------------------------------------------------------------------------
    */
    protected $description = 'Delete read notifications older than a certain period';

    /*
    |--------------------------------------------------------------------------
    | Command Execution
    |--------------------------------------------------------------------------
    */
    public function handle()
    {
        // deleted notifications older than 30 days
        $days = 30;

        // delete notifications
        DB::table('notifications')
            ->whereNotNull('read_at') // only read notifications
            ->where('read_at', '<', Carbon::now()->subDays($days)) // old notifications
            ->delete();

        $this->info('Read notifications older than ' . $days . ' days have been deleted.');
    }
}
