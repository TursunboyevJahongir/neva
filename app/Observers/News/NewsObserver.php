<?php

namespace App\Observers\News;

use App\Models\News;
use Illuminate\Support\Str;

class NewsObserver
{
    public function creating(News $news)
    {
        $news->slug = Str::slug($news->name);
    }

    public function updating(News $news)
    {
        $news->slug = Str::slug($news->name);
    }

    public function deleting(News $news)
    {
        if ($news->image()->exists()) {
            $news->image->removeFile();
            $news->image()->delete();
        }
    }
}
