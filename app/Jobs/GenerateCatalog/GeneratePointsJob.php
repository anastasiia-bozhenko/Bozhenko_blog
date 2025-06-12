<?php

namespace App\Jobs\GenerateCatalog;

class GeneratePointsJob extends AbstractJob
{

    public function handle()
    {
        // === ЦЕЙ РЯДОК БУДЕ РОЗКОМЕНТОВАНО ДЛЯ СИМУЛЯЦІЇ ПОМИЛКИ ПІЗНІШЕ В ЛАБІ ===
        // $f = 1 / 0; // симулюємо помилку
        // =====================================================================

        parent::handle(); // Викликаємо handle() батьківського класу (AbstractJob) для логування "done"
    }
}
