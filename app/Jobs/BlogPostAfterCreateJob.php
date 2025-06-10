<?php

namespace App\Jobs;

use App\Models\BlogPost; // ДОДАЄМО: Імпорт моделі BlogPost
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BlogPostAfterCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Модель блог-посту, яка була створена.
     * @var BlogPost
     */
    private $blogPost; // ДОДАЄМО ЦЮ ВЛАСТИВІСТЬ

    /**
     * Створює новий екземпляр завдання.
     *
     * @param BlogPost $blogPost Об'єкт моделі BlogPost
     * @return void
     */
    public function __construct(BlogPost $blogPost) // ПРАВИМО: Приймаємо об'єкт BlogPost
    {
        $this->blogPost = $blogPost;
    }

    /**
     * Виконує завдання.
     * Цей метод містить логіку, яка буде виконана, коли завдання буде витягнуто з черги.
     *
     * @return void
     */
    public function handle() // ПРАВИМО
    {
        logs()->info("Створено новий запис в блозі [{$this->blogPost->id}]");
    }
}
