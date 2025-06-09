<?php

namespace App\Observers;

use App\Models\BlogCategory;
use Illuminate\Support\Str; // ДОДАЄМО: Імпорт класу Str для slug

class BlogCategoryObserver
{
    /**
     * Обробка перед створенням запису.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     * @return void
     */
    public function creating(BlogCategory $blogCategory)
    {
        $this->setSlug($blogCategory);
    }

    /**
     * Обробка перед оновленням запису.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     * @return void
     */
    public function updating(BlogCategory $blogCategory)
    {
        $this->setSlug($blogCategory);
    }

    /**
     * Якщо псевдонім (slug) порожній, генеруємо його з заголовка.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     * @return void
     */
    protected function setSlug(BlogCategory $blogCategory)
    {
        // Якщо slug порожній, генеруємо його з title
        if (empty($blogCategory->slug)) {
            $blogCategory->slug = Str::slug($blogCategory->title);
        }
    }
}
