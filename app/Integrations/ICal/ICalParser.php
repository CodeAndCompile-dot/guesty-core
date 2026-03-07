<?php

namespace App\Integrations\ICal;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Parses iCalendar (.ics) content from URLs or raw strings.
 * Ported from legacy LiveCart::getParseString(), getMySQLDate(), icsToArray().
 */
class ICalParser
{
    /**
     * Fetch and parse an .ics URL into an array of events.
     */
    public function parseUrl(string $url): array
    {
        try {
            $content = Http::withoutVerifying()
                ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; PhluxuryStays/1.0)'])
                ->timeout(30)
                ->get($url)
                ->body();
        } catch (\Throwable $e) {
            Log::warning('iCal fetch failed', ['url' => $url, 'error' => $e->getMessage()]);

            return [];
        }

        if (empty($content) || strpos($content, 'BEGIN:VCALENDAR') === false) {
            return [];
        }

        return $this->parseString($content);
    }

    /**
     * Parse raw iCalendar string into array of events.
     * Each event has: start_date, end_date, text, event_id
     *
     * Preserves legacy getParseString() behavior exactly.
     */
    public function parseString(string $str): array
    {
        $arr_n = [];
        $arr = explode('BEGIN:VEVENT', $str);

        for ($x = 1; $x < count($arr); $x++) {
            $arr2 = explode("\n", $arr[$x]);

            for ($y = 1; $y < count($arr2); $y++) {
                $mas = explode(':', $arr2[$y]);
                $mas_ = explode(';', $mas[0]);

                if (isset($mas_[0])) {
                    $mas[0] = $mas_[0];
                }

                switch (trim($mas[0])) {
                    case 'DTSTART':
                        $arr_n[$x]['start_date'] = $this->getMySQLDate($mas[1] ?? '');
                        break;
                    case 'DTEND':
                        $arr_n[$x]['end_date'] = $this->getMySQLDate($mas[1] ?? '');
                        break;
                    case 'RRULE':
                        $this->parseRrule($arr_n[$x], $mas[1] ?? '');
                        break;
                    case 'EXDATE':
                        $this->parseExdate($arr_n[$x], trim($mas[1] ?? ''));
                        break;
                    case 'RECURRENCE-ID':
                        $arr_n[$x]['rec_id'] = $this->getMySQLDate($mas[1] ?? '');
                        break;
                    case 'UID':
                        $arr_n[$x]['event_id'] = trim($mas[1] ?? '');
                        break;
                    case 'SUMMARY':
                        $arr_n[$x]['text'] = trim($mas[1] ?? '');
                        break;
                }
            }

            if (isset($arr_n[$x]['rec_id'])) {
                $arr_n[$x]['event_pid'] = $arr_n[$x]['event_id'];
            }
            if (isset($arr_n[$x]['exdate'])) {
                $arr_n[$x]['event_pid'] = $arr_n[$x]['event_id'];
            }
        }

        return $arr_n;
    }

    /**
     * Convert iCal datetime to MySQL format.
     */
    public function getMySQLDate(string $str): ?string
    {
        preg_match('/[0-9]{8}[T][0-9]{6}/', trim($str), $date);

        if (isset($date[0]) && $date[0] !== '') {
            $y = substr($date[0], 0, 4);
            $mn = substr($date[0], 4, 2);
            $d = substr($date[0], 6, 2);
            $h = substr($date[0], 9, 2);
            $m = substr($date[0], 11, 2);
            $s = substr($date[0], 13, 2);

            return "{$y}-{$mn}-{$d} {$h}:{$m}:{$s}";
        }

        if (strlen(trim($str)) === 8) {
            $y = substr($str, 0, 4);
            $mn = substr($str, 4, 2);
            $d = substr($str, 6, 2);

            return "{$y}-{$mn}-{$d} 00:00:00";
        }

        return null;
    }

    /* ------------------------------------------------------------------ */
    /*  Day / Type Conversion Helpers                                      */
    /* ------------------------------------------------------------------ */

    public function getConvertDay($i, bool $mode = false)
    {
        $a = ['SU', 'MO', 'TU', 'WE', 'TH', 'FR', 'SA'];

        if ($mode) {
            for ($y = 0; $y < count($a); $y++) {
                if ($a[$y] === $i) {
                    return $y;
                }
            }

            return null;
        }

        return $a[$i] ?? null;
    }

    public function getConvertType($i, bool $mode = false)
    {
        $a = ['day' => 'DAILY', 'week' => 'WEEKLY', 'month' => 'MONTHLY', 'year' => 'YEARLY'];

        if ($mode) {
            foreach ($a as $key => $value) {
                if ($value === $i) {
                    return $key;
                }
            }

            return null;
        }

        return $a[$i] ?? null;
    }

    public function getConvertDays(string $n): string
    {
        $a = explode(',', $n);
        $parts = [];

        foreach ($a as $val) {
            $converted = $this->getConvertDay((int) $val);
            if ($converted !== null) {
                $parts[] = $converted;
            }
        }

        return implode(',', $parts);
    }

    /* ------------------------------------------------------------------ */
    /*  Internal                                                           */
    /* ------------------------------------------------------------------ */

    protected function parseRrule(array &$event, string $ruleStr): void
    {
        $rrule = explode(';', $ruleStr);

        foreach ($rrule as $part) {
            $rrule_n = explode('=', $part);

            switch ($rrule_n[0] ?? '') {
                case 'FREQ':
                    $event['type'] = $this->getConvertType($rrule_n[1] ?? '', true);
                    break;
                case 'INTERVAL':
                    $event['count'] = $rrule_n[1] ?? '';
                    break;
                case 'COUNT':
                    $event['extra'] = $rrule_n[1] ?? '';
                    break;
                case 'BYDAY':
                    $bayday = explode(',', $rrule_n[1] ?? '');
                    if (count($bayday) === 1) {
                        if (strlen(trim($bayday[0])) === 3) {
                            $event['day'] = substr($bayday[0], 0, 1);
                            $event['counts'] = $this->getConvertDay(substr($bayday[0], 1, 2), true);
                        } else {
                            $event['days'] = $this->getConvertDay($bayday[0], true);
                        }
                    } else {
                        $dayParts = [];
                        foreach ($bayday as $bd) {
                            $dayParts[] = $this->getConvertDay($bd, true);
                        }
                        $event['days'] = implode(',', $dayParts);
                    }
                    break;
                case 'UNTIL':
                    $event['until'] = $this->getMySQLDate($rrule_n[1] ?? '');
                    break;
            }
        }
    }

    protected function parseExdate(array &$event, string $exdateStr): void
    {
        $exdate = explode(',', $exdateStr);

        if (count($exdate) === 1) {
            $event['exdate'] = $this->getMySQLDate($exdate[0]);
        } else {
            $event['exdate'] = [];
            foreach ($exdate as $ed) {
                $event['exdate'][] = $this->getMySQLDate($ed);
            }
        }
    }
}
