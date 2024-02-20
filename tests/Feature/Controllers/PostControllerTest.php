<?php

namespace Tests\Feature\Controllers;

use App\Enums\PostType;
use App\Interfaces\Services\PostServiceInterface;
use App\Models\Post;
use App\Services\PostService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Log\Logger;
use Mockery;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    /** @var \Mockery\MockInterface|Logger */
    private $loggerMock;

    private readonly string $postTable;

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loggerMock = Mockery::mock(Logger::class);

        $this->app->instance(Logger::class, $this->loggerMock);

        $this->postTable = (new Post())->getTable();
    }

    /**
     * Index tests
     */

    /** @test */
    public function a_guest_can_access_index()
    {
        $response = $this->get(route('index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function index_renders_correct_view(): void
    {
        $response = $this->get(route('index'));
        $response->assertViewIs('posts.index');
    }

    /** @test */
    public function index_view_can_access_published_articles_and_projects(): void
    {
        Post::factory()->count(10)->create(['type' => PostType::Project, 'is_published' => false]);
        Post::factory()->count(15)->create(['type' => PostType::Article, 'is_published' => false]);

        $publishedProjects = Post::factory()->count(22)->create(['type' => PostType::Project, 'is_published' => true]);
        $publishedArticles = Post::factory()->count(37)->create(['type' => PostType::Article, 'is_published' => true]);

        $response = $this->get(route('index'));

        $response->assertViewHas('projects', $publishedProjects);
        $response->assertViewHas('articles', $publishedArticles);
    }

    /**
     * Show tests
     */

    /** @test */
    public function a_guest_can_access_a_single_post()
    {
        $post = $this->createSinglePublishedPost();

        $response = $this->get(route('posts.show', $post->slug));

        $response->assertStatus(200);
        $response->assertSee($post->title);
    }

    /** @test */
    public function show_renders_correct_view(): void
    {
        $post = $this->createSinglePublishedPost();

        $response = $this->get(route('posts.show', $post->slug));

        $response->assertViewIs('posts.show');
    }

    /** @test */
    public function show_view_can_access_post_properties(): void
    {
        $post = $this->createSinglePublishedPost();

        $response = $this->get(route('posts.show', $post->slug));

        $response->assertViewHas('post', $post);
    }

    /** @test */
    public function show_throws_a_404_error_if_post_is_not_found(): void
    {
        $response = $this->get(route('posts.show', 'unexistent-post'));

        $response->assertNotFound();
    }

    /** @test */
    public function show_throws_a_404_error_if_post_is_not_published(): void
    {
        $post = Post::factory()->create(['is_published' => false, 'slug' => 'test']);

        $response = $this->get(route('posts.show', $post->slug));

        $response->assertNotFound();
    }

    /**
     * Create tests
     */

     /** @test */
    public function a_guest_can_access_create_form()
    {
        $response = $this->get(route('posts.create'));
        $response->assertStatus(200);
    }

    /** @test */
    public function create_renders_correct_view(): void
    {
        $response = $this->get(route('posts.create'));
        $response->assertViewIs('posts.create');
    }

    /** @test */
    public function create_view_can_access_post_types(): void
    {
        $response = $this->get(route('posts.create'));

        $response->assertViewHas('postTypes', PostType::cases());
    }

    /**
     * Store tests
     */

    /** @test */
    public function a_guest_can_store_a_post()
    {
        $postAttributes = Post::factory()->make()->toArray();

        $this->post(route('posts.store'), $postAttributes);

        $this->assertDatabaseHas($this->postTable, $postAttributes);
    }

    /** @test */
    public function creator_is_redirected_to_successfully_created_post()
    {
        $postAttributes = Post::factory()->make()->toArray();

        $response = $this->post(route('posts.store'), $postAttributes);

        $response->assertRedirectToRoute('posts.show', $postAttributes['slug']);
    }

    /** @test */
    public function creator_is_redirected_to_create_form_when_there_are_validation_errors()
    {
        $createRoute = route('posts.create');

        $response = $this->from($createRoute)->post(route('posts.store'), []);

        $response->assertRedirect($createRoute);
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function an_error_is_logged_when_exception_is_thrown_during_post_creation()
    {
        $postAttributes = Post::factory()->make()->toArray();

        /** @var \Mockery\MockInterface|PostServiceInterface */
        $serviceMock = Mockery::mock(PostService::class);

        $exceptionMessage = 'Error during post creation';

        $this->app->instance(PostServiceInterface::class, $serviceMock);

        $serviceMock->shouldReceive('storePost')->andThrow(new Exception($exceptionMessage));

        $this->loggerMock
            ->shouldReceive('error')
            ->with("Error during post creation: {$exceptionMessage}")
            ->once();

        $this->post(route('posts.store'), $postAttributes);
    }

    /** @test */
    public function creator_is_redirected_to_create_form_when_exception_is_thrown()
    {
        $createRoute = route('posts.create');

        $postAttributes = Post::factory()->make()->toArray();

        /** @var \Mockery\MockInterface|PostServiceInterface */
        $serviceMock = Mockery::mock(PostService::class);

        $this->app->instance(PostServiceInterface::class, $serviceMock);

        $serviceMock->shouldReceive('storePost')->andThrow(new Exception(''));

        $this->loggerMock->shouldReceive('error');

        $response = $this->from($createRoute)->post(route('posts.store'), $postAttributes);

        $response->assertRedirect($createRoute);

        $response->assertSessionHasErrors();
    }

    private function createSinglePublishedPost(): Post
    {
        return Post::factory()->create(['is_published' => true, 'slug' => 'test']);
    }
}
