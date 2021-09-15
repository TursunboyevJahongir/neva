<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Http\Requests\api\CommentCreateRequest;
use App\Http\Requests\api\CommentUpdateRequest;
use App\Models\Comment;
use App\Services\Comment\CommentService;
use App\Traits\ApiResponser;

class CommentController extends Controller
{
    private $service;
    use ApiResponser;

    public function __construct(CommentService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $comments = $this->service->all();
        return $this->success($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentCreateRequest $request)
    {
        $comment = $this->service->create($request->validated());
        return $this->success($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $id)
    {
        return $this->success($id->load('images:url,imageable_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CommentUpdateRequest $request, Comment $id)
    {

        $comment = $this->service->update($request->validated(), $id);
        return $this->success($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', 'comment');
        $comment = $this->service->delete($comment);
        return $this->success($comment);

    }
}
