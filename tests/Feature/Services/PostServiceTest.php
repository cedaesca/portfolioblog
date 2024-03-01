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

    /** @test */
    public function update_post_throws_exception_if_slug_exists()
    {
        $posts = Post::factory()->count(2)->create();

        $this->expectException(QueryException::class);

        $this->postService->updatePost($posts[1]->slug, ['slug' => $posts[0]->slug]);
    }

    /** @test */
    public function update_post_returns_false_if_post_is_not_found()
    {
        $post = Post::factory()->make()->toArray();

        $isSaved = $this->postService->updatePost('notexistingslug', $post);

        $this->assertFalse($isSaved);
    }

    /** @test */
    public function update_post_modifies_single_attribute()
    {
        $post = Post::factory()->create();

        $modifiedAttribute = ['title' => 'testing update'];

        $isSaved = $this->postService->updatePost($post->slug, $modifiedAttribute);

        $updatedPost = Post::find($post->id);

        $this->assertTrue($isSaved);
        $this->assertEquals($modifiedAttribute['title'], $updatedPost->title);
    }

    /** @test */
    public function update_post_modifies_all_attributes()
    {
        $post = Post::factory()->create()->makeHidden(['created_at', 'updated_at']);

        $modifiedAttributes = Post::factory()->make()->toArray();

        $isSaved = $this->postService->updatePost($post->slug, $modifiedAttributes);

        $this->assertTrue($isSaved);

        $updatedPost = Post::find($post->id);

        foreach ($modifiedAttributes as $key => $value) {
            $actualValue = $updatedPost->{$key};

            if (!$actualValue instanceof PostType) {
                $this->assertEquals($value, $actualValue);
            } else {
                $expectedEnum = PostType::from($value);

                $this->assertTrue($actualValue === $expectedEnum);
            }
        }
    }

    private function seedPosts()
    {
        Post::factory()->count(11)->create(['type' => PostType::Article, 'is_published' => false]);

        Post::factory()->count(20)->create(['type' => PostType::Article, 'is_published' => true]);
        Post::factory()->count(14)->create(['type' => PostType::Project, 'is_published' => true]);

        Post::factory()->count(9)->create(['type' => PostType::Project, 'is_published' => false]);
    }
}
