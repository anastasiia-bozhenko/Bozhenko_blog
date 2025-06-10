<?php

namespace App\Observers;

use App\Models\BlogPost;
use Carbon\Carbon; // ДОДАЄМО: Імпорт класу Carbon
use Illuminate\Support\Str; // ДОДАЄМО: Імпорт класу Str для slug

class BlogPostObserver
{
    /**
     * Обробка перед оновленням запису.
     * Цей метод викликається перед тим, як модель BlogPost буде оновлена в базі даних.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    public function updating(BlogPost $blogPost)
    {
        $this->setPublishedAt($blogPost); // Викликаємо метод для встановлення дати публікації
        $this->setSlug($blogPost);         // Викликаємо метод для встановлення slug
    }

    /**
     * Обробка перед створенням запису.
     * Цей метод викликається перед тим, як модель BlogPost буде створена в базі даних.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    public function creating(BlogPost $blogPost)
    {
        $this->setPublishedAt($blogPost); // Також встановлюємо дату публікації при створенні
        $this->setSlug($blogPost);
        $this->setContentHtml($blogPost);// Також встановлюємо slug при створенні
    }

    /**
     * Якщо поле published_at порожнє і запис має бути опублікований (is_published = true),
     * то генеруємо поточну дату.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    protected function setPublishedAt(BlogPost $blogPost)
    {
        // Перевіряємо, чи is_published встановлено на true і published_at ще не встановлено
        if (empty($blogPost->published_at) && $blogPost->is_published) {
            $blogPost->published_at = Carbon::now();
        }
    }

    /**
     * Якщо псевдонім (slug) порожній, генеруємо його з заголовка.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    protected function setSlug(BlogPost $blogPost)
    {
        // Якщо slug порожній, генеруємо його з title
        if (empty($blogPost->slug)) {
            $blogPost->slug = Str::slug($blogPost->title); // Використовуємо Str::slug()
        }
    }

    /**
     * Заповнює поле content_html на основі content_raw.
     * У цьому прикладі просто копіюємо content_raw в content_html.
     * Якщо потрібна обробка Markdown, тут слід використовувати відповідну бібліотеку.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    protected function setContentHtml(BlogPost $blogPost)
    {
        // Перевіряємо, чи існує content_raw і не є null
        if (isset($blogPost->content_raw)) {
            // Для простоти, просто копіюємо content_raw.
            // У реальному додатку тут могла б бути логіка конвертації Markdown в HTML
            // наприклад: $blogPost->content_html = Markdown::convert($blogPost->content_raw);
            $blogPost->content_html = $blogPost->content_raw;
        } else {
            // Якщо content_raw відсутній, встановлюємо content_html як порожній рядок
            // Щоб уникнути помилки "doesn't have a default value"
            $blogPost->content_html = '';
        }
    }
}
