<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CoreRepository.
 *
 * Репозиторій для роботи з сутністю.
 * Може видавати набори даних.
 * Не може змінювати та створювати сутності.
 */
abstract class CoreRepository
{
    /**
     * @var Model
     */
    protected $model;

    /** CoreRepository constructor */
    public function __construct()
    {
        $this->model = app($this->getModelClass()); //app('App\Models\BlogCategory')
    }

    /**
     * Повертає повне ім'я класу моделі.
     * @return string
     */
    abstract protected function getModelClass(): string;

    /**
     * Повертає "свіжий" об'єкт запиту для взаємодії з моделлю.
     * Забезпечує, що кожен запит починається з чистого аркуша.
     * @return Model|\Illuminate\Database\Eloquent\Builder
     */
    protected function startConditions()
    {
        return clone $this->model;
    }
}
