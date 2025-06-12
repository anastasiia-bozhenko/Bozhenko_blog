<?php

namespace App\Jobs\GenerateCatalog;

use Illuminate\Support\Collection; // Для використання collect()
use Illuminate\Support\Facades\Bus;
use Log; // Для Log::info()

class GenerateCatalogMainJob extends AbstractJob
{

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException // Можливо, ці винятки не будуть кидатися у цьому прикладі
     * @throws \Throwable
     */
    public function handle()
    {
        $this->debug('start'); // Логуємо початок головного завдання

        // Спочатку кешуємо продукти (виконується синхронно, без черги)
        // dispatchNow() виконує завдання негайно, а не відправляє його в чергу.
        Bus::dispatchSync(new GenerateCatalogCacheJob());

        // Створюємо ланцюг завдань формування файлів з цінами
        $chainPrices = $this->getChainPrices();

        // Основні підзавдання, які будуть виконуватися після GenerateGoodsFileJob
        $chainMain = [
            new GenerateCategoriesJob, // Генерація категорій
            new GenerateDeliveriesJob, // Генерація способів доставок
            new GeneratePointsJob,     // Генерація пунктів видачі
        ];

        // Підзавдання, які мають виконуватися останніми (після main-завдань)
        $chainLast = [
            // Архівування файлів і перенесення архіву в публічний каталог
            new ArchiveUploadsJob,
            // Відправка повідомлення зовнішньому сервісу про те, що можна завантажувати новий файл каталога товарів
            new SendPriceRequestJob,
        ];

        // Об'єднуємо всі ланцюжки в один послідовний ланцюг
        $chain = array_merge($chainPrices, $chainMain, $chainLast);

        // Запускаємо GenerateGoodsFileJob з ланцюжком завдань.
        // Завдання в ланцюжку будуть виконуватися послідовно, тільки після успішного завершення попереднього.
        GenerateGoodsFileJob::withChain($chain)->dispatch();
        // Альтернативний синтаксис (закоментований): GenerateGoodsFileJob::dispatch()->chain($chain);

        $this->debug('finish'); // Логуємо завершення головного завдання
    }

    /**
     * Формування ланцюгів підзавдань по генерації файлів з цінами
     *
     * @return array Масив об'єктів Job
     */
    private function getChainPrices()
    {
        $result = [];
        // Припустимо, що у нас є 5 "продуктів", які потрібно розбити на "чанки"
        $products = collect([1, 2, 3, 4, 5]);
        $fileNum = 1;

        // Розбиваємо "продукти" на чанки по 1 елементу і створюємо для кожного чанку GeneratePricesFileChunkJob
        foreach ($products->chunk(1) as $chunk) {
            $result[] = new GeneratePricesFileChunkJob($chunk, $fileNum);
            $fileNum++;
        }

        return $result;
    }
}
