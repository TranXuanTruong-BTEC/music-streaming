<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('spotify:fetch-new-releases')
                 ->dailyAt('06:00')
                 ->timezone('Asia/Ho_Chi_Minh');

        $schedule->command('albums:remove-duplicates')->daily();

        $schedule->command('data:remove-duplicates')->daily();

        // Cập nhật thông tin nghệ sĩ hàng ngày
        $schedule->command('artists:update-images')->dailyAt('08:00');
        
        // Cập nhật thông tin album hàng ngày
        $schedule->command('albums:update-tracks')->dailyAt('08:15');
        
        // Cập nhật số lượng bài hát trong album
        $schedule->command('albums:update-track-counts')->dailyAt('08:30');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
