<?php

namespace App\Models;

use App\Enums\PostType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
