<?php

namespace Tests\Feature\Controllers;

use App\Enums\PostType;
use App\Interfaces\Services\PostServiceInterface;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    private readonly string $postTable;
    private readonly User $user;

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->postTable = (new Post())->getTable();

        $this->user = User::factory()->create();
    }

    /**
     * Index tests
     */

    /** @test */
    public function a_guest_can_access_index()
    {
        $response = $this->get(route('index'));
        $response->assertOk();
    }

    /** @test */
    public function an_user_can_access_index()
    {
        $response = $this->actingAs($this->user)->get(route('index'));
        $response->assertOk();
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

        $response->assertOk();
        $response->assertSee($post->title);
    }

    /** @test */
    public function an_user_can_access_a_single_post()
    {
        $post = $this->createSinglePublishedPost();

        $response = $this->actingAs($this->user)->get(route('posts.show', $post->slug));

        $response->assertOk();
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
    public function a_guest_cannot_access_create_form()
    {
        $response = $this->get(route('posts.create'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function create_renders_correct_view(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('posts.create'));

        $response->assertViewIs('posts.create');
    }

    /** @test */
    public function create_view_can_access_post_types(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('posts.create'));

        $response->assertViewHas('postTypes', PostType::cases());
    }

    /**
     * Store tests
     */

    /** @test */
    public function a_guest_cannot_store_a_post()
    {
        $postAttributes = Post::factory()->make()->toArray();

        $response = $this->post(route('posts.store'), $postAttributes);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing($this->postTable, $postAttributes);
    }

    /** @test */
    public function an_user_can_create_a_post()
    {
        $postAttributes = Post::factory()->make()->toArray();

        $this->actingAs($this->user)
            ->post(route('posts.store'), $postAttributes);

        $this->assertDatabaseHas($this->postTable, $postAttributes);
    }

    /** @test */
    public function user_is_redirected_to_successfully_created_post()
    {
        $postAttributes = Post::factory()->make()->toArray();

        $response = $this->actingAs($this->user)
            ->post(route('posts.store'), $postAttributes);

        $response->assertRedirectToRoute('posts.show', $postAttributes['slug']);
    }

    /** @test */
    public function user_is_redirected_to_create_form_when_there_are_validation_errors()
    {
        $createRoute = route('posts.create');

        $response = $this->actingAs($this->user)
            ->from($createRoute)
            ->post(route('posts.store'), []);

        $response->assertRedirect($createRoute);
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function user_is_redirected_to_create_form_when_exception_is_thrown()
    {
        $createRoute = route('posts.create');

        $postAttributes = Post::factory()->make()->toArray();

        /** @var \Mockery\MockInterface|PostServiceInterface */
        $serviceMock = $this->mock(PostService::class);

        $this->app->instance(PostServiceInterface::class, $serviceMock);

        $serviceMock->shouldReceive('storePost')->andThrow(new Exception(''));

        $response = $this->actingAs($this->user)
            ->from($createRoute)
            ->post(route('posts.store'), $postAttributes);

        $response->assertRedirect($createRoute);

        $response->assertSessionHasErrors();
    }

    /**
     * Edit tests
     */

     /** @test */
     public function a_guest_cannot_access_edit_form()
    {
        $post = $this->createSinglePublishedPost();

        $response = $this->get(route('posts.edit', $post->slug));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function edit_renders_correct_view(): void
    {
        $post = $this->createSinglePublishedPost();

        $response = $this->actingAs($this->user)
            ->get(route('posts.edit', $post->slug));

        $response->assertViewIs('posts.edit');
    }

    /**
     * Update tests
     */

     /** @test */
    public function a_guest_cannot_update_a_post()
    {
        $post = $this->createSinglePublishedPost();

        $response = $this->put(route('posts.update', $post->slug), ['title' => 'test']);

        $freshPost = $post->fresh();

        $response->assertRedirect(route('login'));

        $this->assertEquals($post->updated_at, $freshPost->updated_at);
    }

    /** @test */
    public function an_user_can_update_a_post()
    {
        $post = $this->createSinglePublishedPost();

        $modifiyingAttribute = ['title' => 'test'];

        $this->actingAs($this->user)
            ->put(route('posts.update', $post->slug), $modifiyingAttribute);

        $freshPost = $post->fresh();

        $this->assertNotEquals($post->updated_at, $freshPost->updated_at);
        $this->assertEquals($post->title, $modifiyingAttribute['title']);
    }

    private function createSinglePublishedPost(): Post
    {
        return Post::factory()->create(['is_published' => true, 'slug' => 'test']);
    }
}
