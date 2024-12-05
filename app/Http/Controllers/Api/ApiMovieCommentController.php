<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Movie;
use Illuminate\Http\Request;

class ApiMovieCommentController extends Controller
{
    public function index($movieId)
    {
        $movie = Movie::findOrFail($movieId);

        $comments = $movie->comments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return CommentResource::collection($comments)->response();
    }

    public function store(Request $request, $movieId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $movie = Movie::findOrFail($movieId);

        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'content' => $request->input('content'),
        ]);

        $movie->comments()->attach($comment->id);

        return response()->json(['message' => 'Comment added successfully.', 'comment' => new CommentResource($comment)], 201);
    }

    public function destroy(Request $request, $commentId)
    {
        $comment = Comment::findOrFail($commentId);

        if ($request->user()->id !== $comment->user_id && $request->user()->role !== 1) {
            return response()->json(['message' => 'You do not have permission to delete this comment.'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully.'], 200);
    }
}
