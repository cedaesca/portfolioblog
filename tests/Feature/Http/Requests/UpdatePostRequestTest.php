<?php

namespace Tests\Feature\Http\Requests;

use App\Enums\PostType;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;

class UpdatePostRequestTest extends TestCase
{
    const SHOULD_HAVE_ERRORS = true;
    const TEST_POST_SLUGS = ['testing-first-post', 'testing-second-post'];

    use RefreshDatabase;

    private User $user;

    /** @var Post[] */
    private array $posts;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $this->posts = [
            // This is the primary post used to edit and test against
            Post::factory()->create(['slug' => self::TEST_POST_SLUGS[0]]),

            // This is a secondary post to test extra validation
            // rules, for example, slug uniqueness
            Post::factory()->create(['slug' => self::TEST_POST_SLUGS[1]])
        ];
    }

    /**
     * @test
     * @dataProvider provideTitleValidationRulesData
     */
    public function title_validation_rules(string|int $title, bool $shouldHaveErrors)
    {
        $response = $this->makeAuthenticatedUpdateRequest(['title' => $title]);

        $this->assertSessionErrorForAttribute($response, $shouldHaveErrors, 'title');
    }

    /**
     * @test
     * @dataProvider provideContentValidationRulesData
     */
    public function content_validation_rules($content, bool $shouldHaveErrors)
    {
        $response = $this->makeAuthenticatedUpdateRequest(['content' => $content]);

        $this->assertSessionErrorForAttribute($response, $shouldHaveErrors, 'content');
    }

    /**
     * @test
     * @dataProvider provideTypeValidationRulesData
     */
    public function type_validation_rules(string $type, bool $shouldHaveErrors)
    {
        $response = $this->makeAuthenticatedUpdateRequest(['type' => $type]);

        $this->assertSessionErrorForAttribute($response, $shouldHaveErrors, 'type');
    }

    /**
     * @test
     * @dataProvider provideSlugValidationRulesData
     */
    public function slug_validation_rules(string $slug, bool $shouldHaveErrors)
    {
        $response = $this->makeAuthenticatedUpdateRequest(['slug' => $slug]);

        $this->assertSessionErrorForAttribute($response, $shouldHaveErrors, 'slug');
    }

    /**
     * @test
     * @dataProvider provideIsPublishedValidationRulesData
     */
    public function is_published_validation_rules($isPublished, bool $shouldHaveErrors)
    {
        $response = $this->makeAuthenticatedUpdateRequest(['is_published' => $isPublished]);

        $this->assertSessionErrorForAttribute($response, $shouldHaveErrors, 'is_published');
    }

    /** @test */
    public function form_validation_passes_with_valid_bulk_data()
    {
        $modifyingAttributes = Post::factory()->make()->toArray();

        $response = $this->makeAuthenticatedUpdateRequest($modifyingAttributes);

        $response->assertSessionDoesntHaveErrors();
    }

    public static function provideTitleValidationRulesData()
    {
        return [
            'string | invalid integer input' => [1, self::SHOULD_HAVE_ERRORS],
            'string | invalid large integer input' => [12345678, self::SHOULD_HAVE_ERRORS],
            'string | valid string input' => ['test', !self::SHOULD_HAVE_ERRORS],
            'min:3 | lesser than 3 characters' => ['te', self::SHOULD_HAVE_ERRORS],
            'min:3 | equal or greater than 3 characters' => ['tes', !self::SHOULD_HAVE_ERRORS],
            'max:255 | greater than 255 characters' => [Str::random(256), self::SHOULD_HAVE_ERRORS],
            'max:255 | lesser than 255 characters' => [Str::random(100), !self::SHOULD_HAVE_ERRORS],
        ];
    }

    public static function provideContentValidationRulesData()
    {
        return [
            'string | invalid array input' => [[0,1], self::SHOULD_HAVE_ERRORS],
            'string | valid string input' => ['This is a test.', !self::SHOULD_HAVE_ERRORS]
        ];
    }

    public static function provideTypeValidationRulesData()
    {
        $article = PostType::Article->value;
        $project = PostType::Project->value;
        $randomWord = fake()->word();

        return [
            "{$article} | valid enum type input" => [$article, !self::SHOULD_HAVE_ERRORS],
            "{$project} | valid enum type input" => [$project, !self::SHOULD_HAVE_ERRORS],
            "{$randomWord} | invalid type enum input" => [$randomWord, self::SHOULD_HAVE_ERRORS]
        ];
    }

    public static function provideSlugValidationRulesData()
    {
        return [
            'unique | invalid duplicated input' => [self::TEST_POST_SLUGS[1], self::SHOULD_HAVE_ERRORS],
            'unique | valid non duplicated input' => ['randomslug', !self::SHOULD_HAVE_ERRORS],
            'unique | valid current slug input' => [self::TEST_POST_SLUGS[0], !self::SHOULD_HAVE_ERRORS],
            'min:3 | lesser than 3 characters' => ['te', self::SHOULD_HAVE_ERRORS],
            'min:3 | equal or greater than 3 characters' => ['tes', !self::SHOULD_HAVE_ERRORS],
        ];
    }

    public static function provideIsPublishedValidationRulesData()
    {
        return [
            'boolean | invalid integer input' => [5, self::SHOULD_HAVE_ERRORS],
            'boolean | invalid string input' => ['test', self::SHOULD_HAVE_ERRORS],
            'boolean | valid boolean input' => [true, !self::SHOULD_HAVE_ERRORS],
            'boolean | valid boolean input' => [false, !self::SHOULD_HAVE_ERRORS],
        ];
    }

    private function assertSessionErrorForAttribute(TestResponse $response, bool $shouldHaveErrors, string $attribute)
    {
        if ($shouldHaveErrors) {
            $response->assertSessionHasErrors($attribute);
        } else {
            $response->assertSessionDoesntHaveErrors($attribute);
        }
    }

    private function makeAuthenticatedUpdateRequest(array $data): TestResponse
    {
        return $this
            ->actingAs($this->user)
            ->put(route('posts.update', $this->posts[0]->slug), $data);
    }
}