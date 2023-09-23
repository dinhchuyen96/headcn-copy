<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        Log::info('Schedule task is running ...');
        Log::info(config('smstime.smsTimeConfig'));
        //
        $schedule->command('command:WarrantyClaimSMS')
            ->dailyAt(config('smstime.smsTimeConfig.warranty_calim'));
        $schedule->command('command:WrongTimeSMS')
            ->dailyAt(config('smstime.smsTimeConfig.wrong_time'));
        $schedule->command('command:ApplyInsuranceSMS')
            ->dailyAt(config('smstime.smsTimeConfig.apply_insurance'));
        $schedule->command('command:LatePaymentSMS')
            ->dailyAt(config('smstime.smsTimeConfig.late_payment'));
        $schedule->command('command:WarningUrgent')
            ->dailyAt(config('smstime.smsTimeConfig.warning_urgent'));
        $schedule->command('command:AccessoriesSMS')
            ->dailyAt(config('smstime.smsTimeConfig.acccessories'));
        $schedule->command('command:TotalSaleSMS')
            ->dailyAt(config('smstime.smsTimeConfig.total_sale'));
        $schedule->command('command:OverDueCustomerSMS')
            ->dailyAt(config('smstime.smsTimeConfig.overdue_customer'));
        $schedule->command('command:CustomerCareService')
            ->dailyAt(config('smstime.smsTimeConfig.ktdk'));
        $schedule->command('command:CustomerBirthdayService')
            ->dailyAt(config('smstime.smsTimeConfig.birthday'));
        Log::info('Schedule task is finished');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
