<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BlogPostAfterDeleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * ID видаленого блог-посту.
     * @var int
     */
    private $blogPostId; // ДОДАЄМО ЦЮ ВЛАСТИВІСТЬ

    /**
     * Створює новий екземпляр завдання.
     *
     * @param int $blogPostId ID видаленого блог-посту
     * @return void
     */
    public function __construct($blogPostId) // ПРАВИМО: Приймаємо ID посту
    {
        $this->blogPostId = $blogPostId;
    }

    /**
     * Виконує завдання.
     *
     * @return void
     */
    public function handle() // ПРАВИМО
    {
        logs()->warning("Видалено запис в блозі [{$this->blogPostId}]");
    }
}
