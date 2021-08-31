<?php


namespace App\Services\Shop;


use App\Models\Shop;
use App\Models\Image;

class ShopService
{
    /**
     * @var Image
     */
    private $image;

    public function __construct()
    {
        $this->image = new Image();
    }

    public function all()
    {
        return Shop::with('image')
            ->latest('id')
            ->get();
    }

    public function create(array $attributes)
    {
        $shop = Shop::create($attributes);

        $file = $this->image->uploadFile($attributes['image'], 'shops');

        $shop->image()->create([
            'url' => '/'.$file
        ]);

        return $shop;
    }

    public function update(array $attributes, Shop $shop)
    {
        $shop->update($attributes);

        if (array_key_exists('image', $attributes)) {
            if ($shop->image()->exists()) {
                $shop->image->removeFile();
                $shop->image()->delete();
            }

            $file = $this->image->uploadFile($attributes['image'], 'shops');

            $shop->image()->create([
                'url' => '/'.$file
            ]);
        }

        return $shop;
    }

    public function find($user_id)
    {
        return Shop::with('orders', 'customers')
            ->where('user_id', $user_id)
            ->first();
    }

    public function delete(Shop $shop)
    {
        return $shop->delete();
    }

    public function getStatistics(Shop $shop, $start_date, $end_date)
    {
        return $shop->orders()->whereBetween('created_at', [$start_date, $end_date])->count();
    }
}
