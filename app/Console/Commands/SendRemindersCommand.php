<?php

namespace App\Console\Commands;

use App\Services\Communication\ReminderService;
use Illuminate\Console\Command;

class SendRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'communication:send-reminders';

    /**
     * The console command description.
     */
    protected $description = 'Send payment-instalment reminder emails for upcoming bookings';

    public function handle(ReminderService $service): int
    {
        $count = $service->process();

        $this->info("Reminder emails sent: {$count}");

        return self::SUCCESS;
    }
}
