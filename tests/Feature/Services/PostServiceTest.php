<?php

namespace Tests\Feature\Services;

use App\Enums\PostType;
use App\Interfaces\Services\PostServiceInterface;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostServiceTest extends TestCase
{
    private PostServiceInterface $postService;

    use RefreshDatabase;
    

    protected function setUp(): void
    {
        parent::setUp();

        $this->postService = $this->app->make(PostServiceInterface::class);
    }
    
    /** @test */
    public function get_all_projects_returns_projects(): void
    {
        $this->seedPosts();

        $projects = $this->postService->getAllProjects();

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
    public function get_all_articles_returns_articles(): void
    {
        $this->seedPosts();

        $articles = $this->postService->getAllArticles();

        $mismatchMessages = [];

        $this->assertCount(20, $articles);

        foreach ($articles as $article) {
            if ($article->type != PostType::Article) {
                $mismatchMessages[] = "{$article->id} is not an article.";
            }
        }

        $this->assertEmpty($mismatchMessages, implode("\n", $mismatchMessages));
    }

    private function seedPosts()
    {
        Post::factory()->count(20)->create(['type' => PostType::Article]);
        Post::factory()->count(14)->create(['type' => PostType::Project]);
    }
}
