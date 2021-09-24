<?php


namespace App\Http\Controllers;

use App\Models\District;
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

    public function openJson()
    {
        $json = file_get_contents(public_path('Regions.json'));
        $json = json_decode($json, true);

        $data = [];
        $arr = [];
        $saveData = [];

        foreach ($json['data'] as $item) {
            $sato_str = strlen($item["code"]);
            if ($sato_str === 4) {
                $district = District::create([
                    'name' => [
                        'uz' => $item["name_uz"],
                        'ru' => $item["name_ru"],
                        'en' => $item["name_uz"]
                    ],
                    'parent_id' => null,
                    'code' => $item["code"]
                ]);

                array_push($saveData, $data);
                $arr[$district->id] = (int)$item['code'];
            }
        }
        foreach ($json['data'] as $item) {
            $sato_str = strlen($item["code"]);
            if ($sato_str === 7) {

                if (substr($item["code"], -2) === "00") {
                    continue;
                }
                $parent_id = array_search(substr((int)$item["code"], 0, 4), $arr);

                District::create([
                    'name' => [
                        'uz' => $item["name_uz"],
                        'ru' => $item["name_ru"],
                        'en' => $item["name_uz"]
                    ],
                    'parent_id' => $parent_id,
                    'code' => $item["code"]
                ]);

                array_push($saveData, $data);
            }
        }
    }
}
