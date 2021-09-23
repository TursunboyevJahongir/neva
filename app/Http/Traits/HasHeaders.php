<?php


namespace App\Http\Traits;


use App\Enums\DeviceHeadersEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

/**
 * Trait HasHeaders
 * @package App\Traits
 * Please, use this trait only with Illuminate\Foundation\Http\FormRequest or Illuminate\Foundation\Http\Request
 */
trait HasHeaders
{
    /**
     * @return string|null
     */
    public function getDeviceType(): ?string
    {
        /**
         * @var Request|FormRequest $this
         */
        return $this->headers->get(DeviceHeadersEnum::deviceType());
    }

    /**
     * @return string|null
     */
    public function getDeviceModel(): ?string
    {
        /**
         * @var Request|FormRequest $this
         */
        return $this->headers->get(DeviceHeadersEnum::deviceModel());
    }


    /**
     * @return string|null
     */
    public function getDeviceAppVersion(): ?string
    {
        /**
         * @var Request|FormRequest $this
         */
        return $this->headers->get(DeviceHeadersEnum::deviceAppVersion());
    }

    /**
     * @return string|null
     */
    public function getDeviceOSVersion(): ?string
    {
        /**
         * @var Request|FormRequest $this
         */
        return $this->headers->get(DeviceHeadersEnum::deviceOSVersion());
    }

    /**
     * @return string|null
     */
    public function getDeviceLang(): ?string
    {
        /**
         * @var Request|FormRequest $this
         */
        return $this->headers->get(DeviceHeadersEnum::deviceLang(),'ru');
    }

    /**
     * @return string|null
     */
    public function getDevicePushToken(): ?string
    {
        /**
         * @var Request|FormRequest $this
         */
        return $this->headers->get(DeviceHeadersEnum::devicePushToken());
    }

    /**
     * @return string|null
     */
    public function getDeviceUID(): ?string
    {
        /**
         * @var Request|FormRequest $this
         */
        return $this->headers->get(DeviceHeadersEnum::deviceUID());
    }

    /**
     * @param array $headers
     * @return bool
     */
    public function hasHeaders(array $headers): bool
    {
        /**
         * @var Request|FormRequest $this
         */
        $headers = array_keys($headers);
        foreach ($headers as $key) {
            if (array_key_exists($key, $this->headers->all())) {
                continue;
            }
            return false;
        }
        return true;
    }
}
