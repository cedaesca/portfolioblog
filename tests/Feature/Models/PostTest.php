<?php

namespace Tests\Feature\Models;

use App\Enums\PostType;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function article_scope_returns_articles_only(): void
    {
        $this->seedPosts();

        $articles = Post::article()->get();

        $mismatchMessages = [];

        $this->assertCount(22, $articles);

        foreach ($articles as $article) {
            if ($article->type != PostType::Article) {
                $mismatchMessages[] = "{$article->id} is not an article.";
            }
        }

        $this->assertEmpty($mismatchMessages, implode("\n", $mismatchMessages));
    }

    /** @test */
    public function project_scope_returns_projects_only(): void
    {
        $this->seedPosts();

        $projects = Post::project()->get();

        $mismatchMessages = [];

        $this->assertCount(14, $projects);

        foreach ($projects as $project) {
            if ($project->type != PostType::Project) {
                $mismatchMessages[] = "{$project->id} is not a project.";
            }
        }

        $this->assertEmpty($mismatchMessages, implode("\n", $mismatchMessages));
    }

    /** @test */
    public function published_scope_returns_published_posts_only(): void
    {
        Post::factory()->count(17)->create(['is_published' => true]);
        Post::factory()->count(34)->create(['is_published' => false]);
        Post::factory()->count(3)->create(['is_published' => true]);

        $posts = Post::published()->get();

        $this->assertCount(20, $posts);
    }

    private function seedPosts()
    {
        Post::factory()->count(22)->create(['type' => PostType::Article]);
        Post::factory()->count(14)->create(['type' => PostType::Project]);
    }
}
