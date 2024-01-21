<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'css_background_color',
        'css_text_color',
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
