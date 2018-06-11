<?php
namespace App\Console;
use DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
    \App\Console\Commands\Inspire::class,
    ];
    protected function schedule(Schedule $schedule){
        $schedule->call(function () {
            DB::table('payments')->where('status', 0)->delete();
            DB::table('payments_skins')->where('status', 0)->delete();
        })->everyMinute();
    }
}