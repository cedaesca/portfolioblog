<?php

namespace Tests\Feature\Controllers;

use App\Enums\PostType;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Index tests
     */

    /** @test */
    public function a_guest_can_access_index()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function index_renders_correct_view(): void
    {
        $response = $this->get('/');
        $response->assertViewIs('posts.index');
    }

    /** @test */
    public function index_view_can_access_published_articles_and_projects(): void
    {
        Post::factory()->count(10)->create(['type' => PostType::Project, 'is_published' => false]);
        Post::factory()->count(15)->create(['type' => PostType::Article, 'is_published' => false]);

        $publishedProjects = Post::factory()->count(22)->create(['type' => PostType::Project, 'is_published' => true]);
        $publishedArticles = Post::factory()->count(37)->create(['type' => PostType::Article, 'is_published' => true]);

        $response = $this->get('/');

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

    private function createSinglePublishedPost(): Post
    {
        return Post::factory()->create(['is_published' => true, 'slug' => 'test']);
    }
}
