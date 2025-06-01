<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Додаємо цей рядок
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Додаємо для softDeletes

class BlogPost extends Model
{
    use HasFactory; // Додаємо сюди
    use SoftDeletes; // Додаємо сюди для softDeletes

    // Якщо ви хочете дозволити масове присвоєння для цих полів,
    // додайте їх у властивість $fillable:
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
}
