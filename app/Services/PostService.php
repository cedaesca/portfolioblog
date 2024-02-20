<?php

namespace App\Services;

use App\Interfaces\Services\PostServiceInterface;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

class PostService implements PostServiceInterface
{
    public function __construct(private readonly Post $postModel) {}

    public function getAllPublishedProjects(): Collection
    {
        return $this->postModel
            ->published()
            ->project()
            ->get();
    }

    public function getAllPublishedArticles(): Collection
    {
        return $this->postModel
            ->published()
            ->article()
            ->get();
    }

    public function getPublishedPostBySlug(string $slug): ?Post
    {
        return $this->postModel
            ->published()
            ->whereSlug(strtolower($slug))
            ->first();
    }

    public function storePost(array $attributes): Post
    {
        $post = $this->postModel->newInstance($attributes);

        $post->save();

        return $post;
    }
}