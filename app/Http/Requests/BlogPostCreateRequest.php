<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogPostCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Визначає, чи авторизований користувач зробити цей запит.
     *
     * @return bool
     */
    public function authorize()
    {
        // Для цілей лабораторної дозволяємо доступ для всіх.
        // У реальному проекті тут мала б бути перевірка авторизації, наприклад:
        // return auth()->check();
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * Отримує правила валідації, що застосовуються до запиту.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'       => 'required|min:5|max:200',
            'slug'        => 'max:200|unique:blog_posts', // Slug має бути унікальним
            'excerpt'     => 'max:500',
            'content_raw' => 'required|string|min:5|max:10000',
            'category_id' => 'required|integer|exists:blog_categories,id', // Перевіряємо існування категорії
            'is_published' => 'boolean', // Перевіряємо, що це булеве значення
        ];
    }
}
