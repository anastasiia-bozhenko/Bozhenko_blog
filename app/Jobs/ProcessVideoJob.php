<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log; // Або use Illuminate\Support\Facades\Log;

class ProcessVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Створює новий екземпляр завдання.
     *
     * @return void
     */
    public function __construct()
    {
        // Конструктор може бути порожнім, якщо завдання не потребує даних
    }

    /**
     * Виконує завдання.
     * Це метод, який буде викликаний воркером черги.
     *
     * @return void
     */
    public function handle()
    {
        // Приклад простої логіки: запис у лог
        Log::info('Завдання ProcessVideoJob виконано.');
        // logs()->info('Завдання ProcessVideoJob виконано.'); // Також можна використовувати logs() хелпер
    }
}
