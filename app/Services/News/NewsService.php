<?php

namespace App\Services\News;

use App\Models\Image;
use App\Models\News;

class NewsService
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
        return News::with('image')
            ->latest('id')
            ->get();
    }

    public function create(array $attributes)
    {
        $news = News::create($attributes);

        $file = $this->image->uploadFile($attributes['image'], 'news');

        $news->image()->create([
            'url' => '/'.$file
        ]);

        return $news;
    }

    public function update(array $attributes, News $news)
    {
        $news->update($attributes);

        if (array_key_exists('image', $attributes)) {
            if ($news->image()->exists()) {
                $news->image->removeFile();
                $news->image()->delete();
            }

            $file = $this->image->uploadFile($attributes['image'], 'news');

            $news->image()->create([
                'url' => '/'.$file
            ]);
        }

        return $news;
    }

    public function delete(News $news)
    {
        return $news->delete();
    }
}
