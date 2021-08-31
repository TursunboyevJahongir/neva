<?php

namespace App\Services\Page;

use App\Models\Page;

class PageService
{
    public function all()
    {
        return Page::query()
            ->oldest('id')
            ->paginate(config('app.per_page'));
    }

    public function create(array $attributes)
    {
        return Page::create($attributes);
    }

    public function update(array $attributes, Page $page)
    {
        return $page->update($attributes);
    }

    public function delete(Page $page)
    {
        return $page->delete();
    }
}
