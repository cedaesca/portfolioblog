<?php

namespace Tests\Feature\Controllers;

use App\Enums\PostType;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

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
    public function index_can_access_articles_and_projects(): void
    {
        $projects = Post::factory()->count(10)->create(['type' => PostType::Project]);
        $articles = Post::factory()->count(15)->create(['type' => PostType::Article]);

        $response = $this->get('/');

        $response->assertViewHas('projects', $projects);
        $response->assertViewHas('articles', $articles);
    }
}
