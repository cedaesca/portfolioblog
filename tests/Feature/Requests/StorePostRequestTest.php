<?php

namespace Tests\Feature\Requests;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorePostRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function form_validation_fails_without_required_fields()
    {
        $response = $this->post(route('posts.store'), []);

        $response->assertSessionHasErrors(['title', 'content', 'type', 'slug']);
    }

    /** @test */
    public function title_is_required()
    {
        $response = $this->post(route('posts.store'), [
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
        $response = $this->post(route('posts.store'), [
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
        $response = $this->post(route('posts.store'), [
            'title' => 'Valid Title',
            'content' => 'Valid Content',
            'type' => 'invalid',
            'slug' => 'valid-slug',
            'is_published' => true,
        ]);

        $response->assertSessionHasErrors('type');
    }

    /** @test */
    public function slug_must_be_unique()
    {
        Post::factory()->create(['slug' => 'duplicate-slug']);

        $response = $this->post(route('posts.store'), [
            'title' => 'Valid Title',
            'content' => 'Valid Content',
            'type' => 'article',
            'slug' => 'duplicate-slug',
            'is_published' => true,
        ]);

        $response->assertSessionHasErrors('slug');
    }

    /** @test */
    public function is_published_must_be_boolean()
    {
        $response = $this->post(route('posts.store'), [
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
        $response = $this->post(route('posts.store'), [
            'title' => 'Valid Title',
            'content' => 'Valid content.',
            'type' => 'article',
            'slug' => 'valid-slug',
            'is_published' => '1'
        ]);

        $response->assertSessionDoesntHaveErrors();
        $response->assertRedirect();
    }
}
