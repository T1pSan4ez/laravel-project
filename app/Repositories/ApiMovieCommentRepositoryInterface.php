<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Comment;
use App\Models\Movie;

interface ApiMovieCommentRepositoryInterface
{
    public function getCommentsForMovie(int $movieId, int $perPage): LengthAwarePaginator;

    public function createCommentForMovie(Movie $movie, array $data): Comment;

    public function deleteComment(Comment $comment): bool;
}
