<?php

namespace App\Console\Commands;

use App\Services\Communication\WelcomePackageService;
use Illuminate\Console\Command;

class SendWelcomePackagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'communication:send-welcome-packages';

    /**
     * The console command description.
     */
    protected $description = 'Send welcome-package emails to guests approaching check-in';

    public function handle(WelcomePackageService $service): int
    {
        $count = $service->process();

        $this->info("Welcome-package emails sent: {$count}");

        return self::SUCCESS;
    }
}
