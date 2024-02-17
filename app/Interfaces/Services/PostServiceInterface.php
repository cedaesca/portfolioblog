<?php

namespace App\Interfaces\Services;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

interface PostServiceInterface
{
    /** @return Collection|Post */
    public function getAllProjects(): Collection;

    /** @return Collection|Post */
    public function getAllArticles(): Collection;

    public function getPostBySlug(string $slug): ?Post;
}

