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
        $columns = [
            'id',
            'title',
            'slug',
            'is_published',
            'published_at',
            'user_id',
            'category_id',
        ];

        $result = $this->startConditions($perPage = null)
            ->select($columns)
            ->orderBy('id', 'DESC')
            // Додаємо eager loading (жадібне завантаження) зв'язків 'category' та 'user'
            ->with([
                'category' => function ($query) {
                    // Вибираємо тільки 'id' та 'title' з таблиці категорій
                    $query->select(['id', 'title']);
                },
                // Коротка форма для вибору тільки 'id' та 'name' з таблиці користувачів
                'user:id,name',
            ])
            ->paginate($perPage);

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
