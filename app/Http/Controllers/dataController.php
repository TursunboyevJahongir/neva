<?php


namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Routing\Controller as BaseController;
use Nette\Utils\DateTime;

class dataController extends BaseController
{
    public static function diffDateOnString($datetime1, $datetime2)
    {
//        $datetime1 = new DateTime("2010-06-20");

//        $datetime2 = new DateTime("2011-06-22");

        $difference = $datetime1->diff($datetime2);

        echo 'Difference: ' . $difference->y . ' years, '
            . $difference->m . ' months, '
            . $difference->d . ' days, '
            . $difference->h . ' hours, '
            . $difference->i . ' hour, '
            . $difference->s . ' seconds ';

        return $difference;
    }

    public static function diffMinutesOnString($datetime1, $datetime2): array|string|Translator|Application|null
    {
        $difference = $datetime1->diff($datetime2);
        return __('sms.minutes_diff', ['minutes' => $difference->i, 'seconds' => $difference->s]);
    }
}
