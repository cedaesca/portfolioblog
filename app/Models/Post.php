<?php

namespace App\Models;

use App\Enums\PostType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Post
 *
 * @property int $id
 * @property-write string $slug
 * @property string $title
 * @property string $content
 * @property PostType $type
 * @property int $is_published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|Post article()
 * @method static \Database\Factories\PostFactory factory($count = null, $state = [])
 * @method static Builder|Post newModelQuery()
 * @method static Builder|Post newQuery()
 * @method static Builder|Post project()
 * @method static Builder|Post query()
 * @method static Builder|Post whereContent($value)
 * @method static Builder|Post whereCreatedAt($value)
 * @method static Builder|Post whereId($value)
 * @method static Builder|Post whereIsPublished($value)
 * @method static Builder|Post whereSlug($value)
 * @method static Builder|Post whereTitle($value)
 * @method static Builder|Post whereType($value)
 * @method static Builder|Post whereUpdatedAt($value)
 * @method static Builder|Post published()
 * @mixin \Eloquent
 */
class Post extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'title',
        'content',
        'type',
        'is_published'
    ];

    protected $casts = [
        'type' => PostType::class,
    ];

    /**
     * We cast the slug to always be in lower case
     * whenever we create or update a post.
     */
    protected function slug(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => strtolower($value)
        );
    }

    public function scopeArticle(Builder $query)
    {
        $query->where('type', PostType::Article);
    }

    public function scopeProject(Builder $query)
    {
        $query->where('type', PostType::Project);
    }

    public function scopePublished(Builder $query)
    {
        $query->where('is_published', true);
    }
}
