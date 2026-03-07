<?php

namespace App\Http\Controllers;

use App\Models\IcalEvent;
use App\Services\Calendar\ICalService;
use App\Services\Calendar\PriceLabsSyncService;
use Illuminate\Support\Facades\Log;

/**
 * Public/cron iCal and PriceLabs endpoints.
 * Ports legacy ICalController (calendar refresh, PriceLabs sync, .ics export).
 *
 * Email automation methods (sendWelcomePackage, sendReminderPackage, sendReviewEmail)
 * depend on BookingRequest model and will be added in Phase 9.
 */
class ICalController extends Controller
{
    public function __construct(
        protected ICalService $icalService,
        protected PriceLabsSyncService $priceLabsService,
    ) {}

    /**
     * Export website iCal events for a property to .ics file (identical to legacy getEventsICalObject).
     * Uses direct IcalEvent query + file write (matching legacy byte-for-byte).
     */
    public function getEventsICalObject(int $id): void
    {
        $events = IcalEvent::where(['event_type' => 'website', 'event_pid' => $id])->get();

        $ICAL_FORMAT = 'Ymd\THis\Z';
        $appUrl = config('app.url', 'Laravel');
        $appUrlWithoutHttps = preg_replace('#^https?://#', '', $appUrl);

        $icalObject = "BEGIN:VCALENDAR\nVERSION:2.0\nMETHOD:PUBLISH\nPRODID:-//Laravel//Webdesignvr//EN\n";

        foreach ($events as $event) {
            $uid = strtotime('now').'#'.$event->id.'#saro@'.$appUrlWithoutHttps;
            $icalObject .= "BEGIN:VEVENT\n";
            $icalObject .= 'DTSTART:'.date($ICAL_FORMAT, strtotime($event->start_date))."\n";
            $icalObject .= 'DTEND:'.date($ICAL_FORMAT, strtotime($event->end_date))."\n";
            $icalObject .= 'DTSTAMP:'.date($ICAL_FORMAT, strtotime($event->created_at))."\n";
            $icalObject .= "SUMMARY:{$event->text}\n";
            $icalObject .= "DESCRIPTION:{$event->text}\n";
            $icalObject .= "UID:{$uid}\n";
            $icalObject .= "STATUS:CONFIRMED\n";
            $icalObject .= 'LAST-MODIFIED:'.date($ICAL_FORMAT, strtotime($event->updated_at))."\n";
            $icalObject .= "END:VEVENT\n";
        }

        $icalObject .= 'END:VCALENDAR';

        $file = sprintf('%06d', $id);
        $dir = public_path('uploads/ical');

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($dir.'/'.$file.'.ics', $icalObject);
    }

    /**
     * Refresh all iCal import feeds (admin button + cron).
     */
    public function refresshCalendar()
    {
        $this->icalService->refreshAllImports();

        return redirect()->back()->with('success', 'successfully refreshed');
    }

    /**
     * Sync PriceLabs rates for all configured properties (admin button + cron).
     */
    public function setPriceLab()
    {
        $apiKey = \ModelHelper::getDataFromSetting('pricelab_access_token');

        if (! empty($apiKey)) {
            $this->priceLabsService->syncAll($apiKey);
        }

        return redirect()->back();
    }

    /**
     * Cron job: refresh calendar + send emails + sync PriceLabs.
     * Email methods (sendReminderPackage, sendWelcomePackage) will be added in Phase 9.
     */
    public function setCronJob()
    {
        try {
            $this->icalService->refreshAllImports();
        } catch (\Throwable $e) {
            Log::error('Cron: Calendar refresh failed', ['error' => $e->getMessage()]);
        }

        try {
            $apiKey = \ModelHelper::getDataFromSetting('pricelab_access_token');
            if (! empty($apiKey)) {
                $this->priceLabsService->syncAll($apiKey);
            }
        } catch (\Throwable $e) {
            Log::error('Cron: PriceLabs sync failed', ['error' => $e->getMessage()]);
        }

        // Phase 9 will add: sendReminderPackage, sendWelcomePackage
    }

    /**
     * Stub: Send welcome packages (Phase 9 — BookingRequest dependency).
     */
    public function sendWelcomePackage()
    {
        // Will be implemented in Phase 9 when BookingRequest model exists.
        return redirect()->back();
    }

    /**
     * Stub: Send review emails (Phase 9 — BookingRequest dependency).
     */
    public function sendReviewEmail()
    {
        // Will be implemented in Phase 9 when BookingRequest model exists.
        return redirect()->back();
    }
}
