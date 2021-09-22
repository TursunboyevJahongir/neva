<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\News;

use App\Services\Comment\CommentService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * @var BannerService
     */
    private $service;
    use ApiResponser;

    public function __construct(CommentService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $news = $this->service->all();
        return $this->success($news);
    }

    public function show(News $news)
    {
        $this->success($news);
    }
}
