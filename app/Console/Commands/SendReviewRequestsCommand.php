<?php

namespace App\Console\Commands;

use App\Services\Communication\ReviewRequestService;
use Illuminate\Console\Command;

class SendReviewRequestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'communication:send-review-requests';

    /**
     * The console command description.
     */
    protected $description = 'Send review-request emails to guests after checkout';

    public function handle(ReviewRequestService $service): int
    {
        $count = $service->process();

        $this->info("Review-request emails sent: {$count}");

        return self::SUCCESS;
    }
}
