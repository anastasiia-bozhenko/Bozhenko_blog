<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <<-- ЦЕЙ РЯДОК
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Цей рядок також має бути, якщо ви його додавали

class BlogPost extends Model
{
    use HasFactory; // <<-- І ЦЕЙ РЯДОК
    use SoftDeletes; // І цей рядок, якщо ви його додавали

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'user_id',
        'slug',
        'title',
        'excerpt',
        'content_raw',
        'content_html',
        'is_published',
        'published_at',
    ];

    // ... інший код моделі, якщо є
}
