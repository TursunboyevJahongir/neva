<?php

namespace App\Casts;



use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class TranslatableJson implements CastsAttributes
{

    public function get($model, $key, $value, $attributes)
    {
        $arr = json_decode($value, true);
        return $arr[app()->getLocale()] ?? $arr['ru'] ?? $arr['uz'] ?? new \stdClass();
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, $key, $value, $attributes)
    {
        return json_encode($value);
    }

    /**
     * Get the serialized representation of the value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function serialize($model, string $key, $value, array $attributes)
    {
        return json_decode($attributes[$key], true);
    }
}
