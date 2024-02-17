<?php

namespace App\Services;

use App\Enums\PostType;
use App\Interfaces\Services\PostServiceInterface;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

class PostService implements PostServiceInterface
{
    public function __construct(private Post $postModel) {}

    public function getAllProjects(): Collection
    {
        return $this->postModel->project()->get();
    }

    public function getAllArticles(): Collection
    {
        return $this->postModel->article()->get();
    }
}