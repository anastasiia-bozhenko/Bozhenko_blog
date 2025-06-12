<?php

namespace App\Jobs\GenerateCatalog;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log; // Або use Illuminate\Support\Facades\Log;

// Абстрактний Job, який визначає спільну логіку для всіх Job'ів генерації каталогу
abstract class AbstractJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     * Конструктор встановлює чергу за замовчуванням для всіх нащадків.
     *
     * @return void
     */
    public function __construct()
    {
        // Встановлюємо назву черги за замовчуванням для всіх Job'ів, що успадковуються від цього абстрактного класу.
        // Це означає, що їх буде обробляти воркер, запущений з `--queue=generate-catalog`.
        $this->onQueue('generate-catalog');
    }

    /**
     * Execute the job.
     * Метод handle() для абстрактного класу за замовчуванням просто логує, що завдання виконано.
     * Його можна перевизначити в нащадках.
     *
     * @return void
     */
    public function handle()
    {
        $this->debug('done');
    }

    /**
     * Допоміжний метод для логування, щоб легко відстежувати виконання завдань.
     *
     * @param string $msg Повідомлення для логування
     * @return void
     */
    protected function debug(string $msg)
    {
        $class = static::class; // Отримуємо ім'я класу, який викликав цей метод
        $msg = $msg . " [{$class}]";
        Log::info($msg); // Записуємо повідомлення в лог-файл Laravel
    }
}
