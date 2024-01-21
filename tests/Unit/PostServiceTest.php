<?php

namespace Tests\Unit;

use App\Enums\PostType;
use App\Interfaces\Services\PostServiceInterface;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class PostServiceTest extends TestCase
{
    private PostServiceInterface $postService;
    private Post|MockInterface $postModel;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->postModel = Mockery::mock(Post::class);

        $this->postService = new PostService($this->postModel);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }

    /** @test */
    public function get_all_projects_returns_collection()
    {
        $this->postModel
            ->shouldReceive('where')
            ->with('type', PostType::Project->value)
            ->once()
            ->andReturnSelf();

        $this->postModel
            ->shouldReceive('get')
            ->once()
            ->andReturn($this->createMock(Collection::class));

        $result = $this->postService->getAllProjects();

        $this->assertInstanceOf(Collection::class, $result);
    }

    /** @test */
    public function test_get_all_articles_returns_collection()
    {
        $this->postModel
            ->shouldReceive('where')
            ->with('type', PostType::Article->value)
            ->once()
            ->andReturnSelf();

        $this->postModel
            ->shouldReceive('get')
            ->once()
            ->andReturn($this->createMock(Collection::class));

        $result = $this->postService->getAllArticles();

        $this->assertInstanceOf(Collection::class, $result);
    }
}
