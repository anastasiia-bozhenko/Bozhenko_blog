<?php

namespace App\Repositories;

use App\Models\BlogCategory as Model; // Змінено на "Model" для абстрагування
use Illuminate\Database\Eloquent\Collection; // Додано імпорт для Collection

/**
 * Class BlogCategoryRepository.
 *
 * Репозиторій для роботи з категоріями блогу.
 */
class BlogCategoryRepository extends CoreRepository
{
    /**
     * Повертає повне ім'я класу моделі BlogCategory.
     * @return string
     */
    protected function getModelClass(): string
    {
        return Model::class; // Абстрагування моделі BlogCategory, для легшого створення іншого репозиторія
    }

    /**
     * Отримати модель категорії для редагування в адмінці.
     * @param int $id
     * @return Model|null
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }

    /**
     * Отримати список категорій для виводу в випадаючий список.
     * @return Collection|\Illuminate\Database\Eloquent\Builder[]
     */
    public function getForComboBox()
    {
        $columns = implode(', ', [
            'id',
            // Додаємо нове поле 'id_title', яке буде об'єднанням id та title
            'CONCAT(id, ". ", title) AS id_title',
        ]);

        // toBase() використовується для отримання об'єктів stdClass замість Eloquent-моделей.
        // Це може бути більш продуктивно, якщо вам не потрібен повний функціонал моделі,
        // а лише сирі дані для випадаючого списку.
        $result = $this
            ->startConditions()
            ->selectRaw($columns) // selectRaw дозволяє використовувати сирі SQL-вирази
            ->toBase()           // Отримуємо колекцію stdClass об'єктів
            ->get();

        return $result;
    }

    /**
     * Отримати категорії для виводу пагінатором.
     *
     * @param int|null $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllWithPaginate($perPage = null)
    {
        // Вибираємо тільки необхідні стовпці для оптимізації запиту
        $columns = ['id', 'title', 'parent_id'];

        $result = $this
            ->startConditions()
            ->select($columns)
            ->paginate($perPage); // Застосовуємо пагінацію

        return $result;
    }
}
