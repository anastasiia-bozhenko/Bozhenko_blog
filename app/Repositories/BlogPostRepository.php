<?php

namespace App\Repositories;

use App\Models\BlogPost as Model; // Змінено на "Model" для абстрагування
use Illuminate\Database\Eloquent\Collection; // Додано імпорт для Collection
use Illuminate\Contracts\Pagination\LengthAwarePaginator; // Додано імпорт для інтерфейсу пагінатора

/**
 * Class BlogPostRepository.
 *
 * Репозиторій для роботи зі статтями блогу.
 */
class BlogPostRepository extends CoreRepository
{
    /**
     * Повертає повне ім'я класу моделі BlogPost.
     * @return string
     */
    protected function getModelClass(): string
    {
        return Model::class; // Абстрагування моделі BlogPost
    }

    /**
     * Отримати список статей з пагінацією.
     *
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate(): LengthAwarePaginator
    {
        // Вибираємо тільки необхідні стовпці для відображення у списку
        $columns = [
            'id',
            'title',
            'slug',
            'is_published',
            'published_at',
            'user_id',
            'category_id',
        ];

        $result = $this->startConditions()
            ->select($columns)
            ->orderBy('id', 'DESC') // Сортуємо за ID у спадаючому порядку
            ->paginate(25); // 25 статей на сторінку

        return $result;
    }

    /**
     * Отримати модель для редагування в адмінці.
     * @param int $id
     * @return Model|null
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }
}
