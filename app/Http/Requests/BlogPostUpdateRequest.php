<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogPostUpdateRequest extends FormRequest
{
    /**
     * Визначає, чи авторизований користувач зробити цей запит.
     * Зазвичай тут перевіряється, чи має користувач відповідні дозволи.
     *
     * @return bool
     */
    public function authorize()
    {
        // return->auth()->check(); // Цей рядок закоментовано згідно з інструкцією
        return true; // Для цілей лабораторної дозволяємо доступ для всіх
    }

    /**
     * Отримує правила валідації, що застосовуються до запиту.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'       => 'required|min:5|max:200',
            'slug'        => 'max:200',
            'excerpt'     => 'max:500',
            'content_raw' => 'required|string|min:5|max:10000',
            'category_id' => 'required|integer|exists:blog_categories,id', // Перевіряємо, що категорія існує в таблиці 'blog_categories'
        ];
    }

    /**
     * Отримує повідомлення про помилки для визначених правил валідації.
     * (Необов'язково, можна додати для кастомізації повідомлень)
     *
     * @return array
     */
    /*
    public function messages()
    {
        return [
            'title.required' => 'Поле "Заголовок" є обов\'язковим.',
            'title.min'      => 'Заголовок має бути не менше :min символів.',
            'title.max'      => 'Заголовок має бути не більше :max символів.',
            // Додайте інші повідомлення за потребою
        ];
    }
    */
}
