<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieCommentRequest;
use App\Http\Resources\CommentResource;
use App\Interfaces\ApiMovieCommentRepositoryInterface;
use App\Models\Comment;
use App\Models\Movie;
use Illuminate\Http\Request;

class ApiMovieCommentController extends Controller
{
    protected $movieCommentRepository;

    public function __construct(ApiMovieCommentRepositoryInterface $movieCommentRepository)
    {
        $this->movieCommentRepository = $movieCommentRepository;
    }

    public function index($movieId)
    {
        $comments = $this->movieCommentRepository->getCommentsForMovie($movieId, 10);

        return CommentResource::collection($comments)->response();
    }

    public function store(StoreMovieCommentRequest $request, $movieId)
    {
        $movie = Movie::findOrFail($movieId);

        $comment = $this->movieCommentRepository->createCommentForMovie($movie, [
            'user_id' => $request->user()->id,
            'content' => $request->input('content'),
        ]);

        return response()->json([
            'message' => 'Comment added successfully.',
            'comment' => new CommentResource($comment),
        ], 201);
    }

    public function destroy(Request $request, $commentId)
    {
        $comment = Comment::findOrFail($commentId);

        if ($request->user()->id !== $comment->user_id && $request->user()->role !== 1) {
            return response()->json(['message' => 'You do not have permission to delete this comment.'], 403);
        }

        $this->movieCommentRepository->deleteComment($comment);

        return response()->json(['message' => 'Comment deleted successfully.'], 200);
    }
}
