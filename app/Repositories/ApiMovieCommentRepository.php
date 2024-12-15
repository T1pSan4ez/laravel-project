<?php

namespace App\Repositories;

use App\Interfaces\ApiMovieCommentRepositoryInterface;
use App\Models\Comment;
use App\Models\Movie;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ApiMovieCommentRepository implements ApiMovieCommentRepositoryInterface
{
    public function getCommentsForMovie(int $movieId, int $perPage): LengthAwarePaginator
    {
        $movie = Movie::findOrFail($movieId);

        return $movie->comments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function createCommentForMovie(Movie $movie, array $data): Comment
    {
        $comment = Comment::create([
            'user_id' => $data['user_id'],
            'content' => $data['content'],
        ]);

        $movie->comments()->attach($comment->id);

        return $comment;
    }

    public function deleteComment(Comment $comment): bool
    {
        return $comment->delete();
    }
}
