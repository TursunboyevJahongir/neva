<?php


namespace App\Enums;


use JetBrains\PhpStorm\ArrayShape;
use Spatie\Enum\Enum;

/**
 * Class Headers
 * @package App\Enums
 * @method static self deviceLang()
 */
class DeviceHeadersEnum extends Enum
{
    /**
     * @return string[]
     */
    protected static function
    values(): array
    {
        return [
//            'deviceType' => 'x-device-type',
//            'deviceModel' => 'x-device-model',
//            'deviceAppVersion' => 'x-device-app-version',
//            'deviceOSVersion' => 'x-device-os-version',
            'deviceLang' => 'lang',
//            'devicePushToken' => 'x-device-push-token',
            // 'deviceUID' => 'x-device-uid',
        ];
    }
}
