<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\api\CommentCreateRequest;
use App\Http\Requests\api\CommentUpdateRequest;
use App\Models\Comment;
use App\Services\Comment\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class CommentController extends ApiController
{
    private $service;

    public function __construct(CommentService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        $comments = $this->service->all();
        return $this->success(__('messages.success'), $comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CommentCreateRequest $request
     * @return JsonResponse
     */
    public function store(CommentCreateRequest $request): JsonResponse
    {
        $comment = $this->service->create($request->validated());
        return $this->success(__('messages.success'), $comment);
    }

    /**
     * Display the specified resource.
     *
     * @param Comment $id
     * @return JsonResponse
     */
    public function show(Comment $id): JsonResponse
    {
        return $this->success(__('messages.success'), $id->load('images:url,imageable_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CommentUpdateRequest $request
     * @param Comment $id
     * @return JsonResponse
     */
    public function edit(CommentUpdateRequest $request, Comment $id): JsonResponse
    {

        $comment = $this->service->update($request->validated(), $id);
        return $this->success(__('messages.success'), $comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Comment $comment
     * @return JsonResponse
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $this->authorize('delete', 'comment');
        $comment = $this->service->delete($comment);
        return $this->success(__('messages.success'), $comment);

    }
}
