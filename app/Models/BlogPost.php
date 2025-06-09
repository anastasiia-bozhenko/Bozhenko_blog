<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Переконайтеся, що цей рядок є

class BlogPost extends Model
{
    use HasFactory;
    use SoftDeletes; // Цей рядок має бути відразу після use HasFactory; або будь-яких інших use трейтів

    /**
     * Поля, дозволені для масового заповнення (mass assignment).
     * Це важливо для безпеки, щоб запобігти небажаному заповненню полів.
     *
     * @var array
     */
    protected $fillable
        = [
            'title',
            'slug',
            'category_id',
            'excerpt',
            'content_raw',
            'is_published',
            'published_at',
            'user_id',
        ];

    /**
     * Визначає зв'язок "належить до" (BelongsTo) з моделлю BlogCategory.
     * Це означає, що один блог-пост належить одній категорії.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        // Стаття належить до категорії
        return $this->belongsTo(BlogCategory::class);
    }

    /**
     * Визначає зв'язок "належить до" (BelongsTo) з моделлю User.
     * Це означає, що один блог-пост написаний одним користувачем (автором).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        // Стаття належить користувачу (автору)
        return $this->belongsTo(User::class);
    }
}
