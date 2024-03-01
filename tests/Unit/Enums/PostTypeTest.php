<?php

namespace Tests\Unit\Enums;

use App\Enums\PostType;
use PHPUnit\Framework\TestCase;

class PostTypeTest extends TestCase
{
    /** @test */
    public function post_type_has_article_and_project_cases(): void
    {
        $cases = PostType::cases();

        $this->assertEquals(2, count($cases));
        $this->assertEquals('Article', $cases[0]->name);
        $this->assertEquals('Project', $cases[1]->name);
    }

    /** @test */
    public function article_value_is_in_lower_case()
    {
        $this->assertEquals('article', PostType::Article->value);
    }

    /** @test */
    public function project_value_is_in_lower_case()
    {
        $this->assertEquals('project', PostType::Project->value);
    }
}
