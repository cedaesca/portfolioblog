<?php

namespace Tests\Feature\Services;

use App\Enums\PostType;
use App\Interfaces\Services\PostServiceInterface;
use App\Models\Post;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostServiceTest extends TestCase
{
    private PostServiceInterface $postService;
    private readonly string $postTable;

    use RefreshDatabase;
    

    protected function setUp(): void
    {
        parent::setUp();

        $this->postService = $this->app->make(PostServiceInterface::class);
        $this->postTable = (new Post())->getTable();
    }
    
    /** @test */
    public function get_all_published_projects_returns_published_projects(): void
    {
        $this->seedPosts();

        $projects = $this->postService->getAllPublishedProjects();

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
    public function get_all_published_articles_returns_published_articles(): void
    {
        $this->seedPosts();

        $articles = $this->postService->getAllPublishedArticles();

        $mismatchMessages = [];

        $this->assertCount(20, $articles);

        foreach ($articles as $article) {
            if ($article->type != PostType::Article) {
                $mismatchMessages[] = "{$article->id} is not an article.";
            }
        }

        $this->assertEmpty($mismatchMessages, implode("\n", $mismatchMessages));
    }

    /** @test */
    public function store_post_saves_to_database()
    {
        $postAttributes = Post::factory()->make()->toArray();

        $this->postService->storePost($postAttributes);

        $this->assertDatabaseHas($this->postTable, $postAttributes);
    }

    /** @test */
    public function store_post_throws_exception_with_invalid_data()
    {
        $this->expectException(QueryException::class);

        $this->postService->storePost([]);
    }

    private function seedPosts()
    {
        Post::factory()->count(11)->create(['type' => PostType::Article, 'is_published' => false]);

        Post::factory()->count(20)->create(['type' => PostType::Article, 'is_published' => true]);
        Post::factory()->count(14)->create(['type' => PostType::Project, 'is_published' => true]);
        
        Post::factory()->count(9)->create(['type' => PostType::Project, 'is_published' => false]);
    }
}
