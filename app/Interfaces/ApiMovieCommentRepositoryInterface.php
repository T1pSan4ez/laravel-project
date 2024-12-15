<?php

namespace App\Interfaces;

use App\Models\Comment;
use App\Models\Movie;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ApiMovieCommentRepositoryInterface
{
    public function getCommentsForMovie(int $movieId, int $perPage): LengthAwarePaginator;

    public function createCommentForMovie(Movie $movie, array $data): Comment;

    public function deleteComment(Comment $comment): bool;
}
