<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller as GuestBaseController;

abstract class BaseController extends GuestBaseController
{
    /**
     * BaseController constructor
     */
    public function __construct()
    {
        // Ініціалізація загальних елементів адмінки
        // Тут може бути спільна логіка для всіх адмін-контролерів,
        // наприклад, перевірка прав доступу чи завантаження загальних даних
    }
}
