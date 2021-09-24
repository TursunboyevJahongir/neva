<?php

namespace App\Services\Comment;

use App\Models\Comment;
use App\Models\Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    /**
     * @var Image
     */
    private $image;


    public function all()
    {
        return Comment::with('images:url,imageable_id')
            ->latest('id')
            ->get();
    }

    public function create(array $attributes)
    {
        $this->addUser($attributes);
        //dd($attributes);
        $comment = Comment::create($attributes);

        if (Arr::has($attributes, "images")) {
            for ($i = 0; $i < count($attributes['images']); $i++) {
                // optional comment images
                if (Arr::has($attributes, "images.$i")) {
                    $comment->image()->create([
                        'url' => Image::uploadFile($attributes['images'][$i], 'comment')
                    ]);
                }
            }
            $comment->load('images');
        }

        return $comment;
    }

    public function update(array $attributes, Comment $comment)
    {

        $comment->update($attributes);
        if (Arr::has($attributes, "images"))
        for ($i = 0; $i < count($attributes['images']); $i++)
        if (Arr::has($attributes, "images.$i")) {
            if ($comment->image()->exists()) {
                $comment->image->removeFile();
                $comment->image()->delete();
            }
            $comment->image()->create([
                'url' => Image::uploadFile($attributes['images'][$i], 'comment')
            ]);
        }

        $comment->load('images');
        return $comment;
    }

    public function delete(Comment $comment)
    {
        return $comment->delete();
    }

    public function addUser(&$array)
    {
      $array['user_id']=Auth::id();
    }
}
