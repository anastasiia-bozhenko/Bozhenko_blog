<?php

namespace App\Jobs\GenerateCatalog;

use Illuminate\Support\Collection; // Імпорт для Collection

class GeneratePricesFileChunkJob extends AbstractJob
{
    /**
     * @var Collection Колекція продуктів у цьому чанку
     */
    private $chunk;

    /**
     * @var int Номер файлу чанку
     */
    private $fileNum;

    /**
     * Create a new job instance.
     *
     * @param Collection $chunk
     * @param int $fileNum
     */
    public function __construct(Collection $chunk, int $fileNum)
    {
        parent::__construct(); // Викликаємо конструктор батьківського класу для встановлення черги
        $this->chunk = $chunk;
        $this->fileNum = $fileNum;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->debug('done'); // Логуємо виконання
        // Додаткова логіка для обробки чанку, наприклад:
        // Log::info("Обробка чанку №{$this->fileNum} з продуктами: " . $this->chunk->implode(','));
    }
}
