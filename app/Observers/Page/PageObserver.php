<?php

namespace App\Observers\Page;

use App\Models\Page;
use Illuminate\Support\Str;

class PageObserver
{
    public function creating(Page $page)
    {
        $page->slug = Str::slug($page->name.'-'.$page->id);
    }

    public function updating(Page $page)
    {
        $page->slug = Str::slug($page->name.'-'.$page->id);
    }
}
