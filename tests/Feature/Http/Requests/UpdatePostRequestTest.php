<?php

namespace Tests\Feature\Http\Requests;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdatePostRequestTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $post;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->post = Post::factory()->create();
    }

    /** @test */
    public function form_validation_fails_without_required_fields()
    {
        $response = $this->actingAs($this->user)->post(route('posts.update', $this->post->id), []);
        $response->assertSessionHasErrors(['title', 'content', 'type', 'slug']);
    }

    /** @test */
    public function title_is_required()
    {
        $response = $this->actingAs($this->user)->post(route('posts.update', $this->post->id), [
            'content' => 'Valid Content',
            'type' => 'article',
            'slug' => 'valid-slug',
            'is_published' => true,
        ]);
        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function content_is_required()
    {
        $response = $this->actingAs($this->user)->post(route('posts.update', $this->post->id), [
            'title' => 'Valid Title',
            'type' => 'article',
            'slug' => 'valid-slug',
            'is_published' => true,
        ]);
        $response->assertSessionHasErrors('content');
    }

    /** @test */
    public function type_must_be_valid_enum_value()
    {
        $response = $this->actingAs($this->user)->post(route('posts.update', $this->post->id), [
            'title' => 'Valid Title',
            'content' => 'Valid Content',
            'type' => 'invalid',
            'slug' => 'valid-slug',
            'is_published' => true,
        ]);
        $response->assertSessionHasErrors('type');
    }

    /** @test */
    public function slug_must_be_unique_except_for_the_current_post()
    {
        $otherPost = Post::factory()->create(['slug' => 'other-slug']);

        $response = $this->actingAs($this->user)->post(route('posts.update', $this->post->id), [
            'title' => 'Valid Title',
            'content' => 'Valid Content',
            'type' => 'article',
            'slug' => 'other-slug', // This slug is taken by $otherPost
            'is_published' => true,
        ]);
        $response->assertSessionHasErrors('slug');

        // Test it passes with the current post's slug
        $response = $this->actingAs($this->user)->post(route('posts.update', $this->post->id), [
            'title' => 'Valid Title',
            'content' => 'Valid Content',
            'type' => 'article',
            'slug' => $this->post->slug, // Using its own slug
            'is_published' => true,
        ]);
        $response->assertSessionDoesntHaveErrors();
    }

    /** @test */
    public function is_published_must_be_boolean_if_provided()
    {
        $response = $this->actingAs($this->user)->post(route('posts.update', $this->post->id), [
            'title' => 'Valid Title',
            'content' => 'Valid Content',
            'type' => 'article',
            'slug' => 'valid-slug',
            'is_published' => 'not-a-boolean',
        ]);
        $response->assertSessionHasErrors('is_published');
    }

    /** @test */
    public function form_validation_passes_with_valid_data()
    {
        $response = $this->actingAs($this->user)->post(route('posts.update', $this->post->id), [
            'title' => 'Updated Valid Title',
            'content' => 'Updated Valid content.',
            'type' => 'article',
            'slug' => 'updated-valid-slug',
            'is_published' => true,
        ]);
        $response->assertSessionDoesntHaveErrors();
        $response->assertRedirect();
    }
}
