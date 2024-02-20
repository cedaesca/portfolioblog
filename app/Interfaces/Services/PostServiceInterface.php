<?php

namespace App\Interfaces\Services;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

interface PostServiceInterface
{
    /** @return Collection|Post */
    public function getAllPublishedProjects(): Collection;

    /** @return Collection|Post */
    public function getAllPublishedArticles(): Collection;

    public function getPublishedPostBySlug(string $slug): ?Post;

    public function storePost(array $attributes): Post;
}

