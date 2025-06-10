<?php

namespace App\Http\Controllers;

use App\Models\BlogPost; // Імпортуємо модель BlogPost для роботи з нею
use Carbon\Carbon;      // Імпортуємо Carbon для роботи з датами

class DiggingDeeperController extends Controller
{
    /**
     * Базова інформація про колекції
     * @url https://laravel.com/docs/11.x/collections#introduction
     *
     * Довідкова інформація про Illuminate\Support\Collection
     * @url https://laravel.com/api/11.x/Illuminate/Support\Collection.html
     *
     * Варіант колекції для моделі Eloquent
     * @url https://laravel.com/api/11.x/Illuminate\Database\Eloquent\Collection.html
     *
     */
    public function collections()
    {
        $result = []; // Масив для зберігання результатів демонстрації

        /**
         * Отримуємо всі записи моделі BlogPost, включаючи "м'яко видалені" (soft deleted).
         * Результатом буде екземпляр Illuminate\Database\Eloquent\Collection.
         *
         * Ми одразу ж перетворюємо Eloquent колекцію на базову Support\Collection,
         * трансформуючи кожен елемент, щоб гарантувати наявність `created_at` як об'єкта Carbon.
         * Це запобігає проблемі Undefined property пізніше.
         * @var \Illuminate\Support\Collection $collection
         */
        $collection = BlogPost::withTrashed()->get()->map(function ($item) {
            // Eloquent модель вже кастує дати в Carbon, тому $item->created_at вже буде об'єктом Carbon
            // Або null, якщо поле порожнє.
            // Ми перетворюємо модель на stdClass, щоб зберегти її в загальній колекції.
            $newItem = new \stdClass();
            $newItem->item_id = $item->id;
            $newItem->item_name = $item->title;
            $newItem->exists = is_null($item->deleted_at);
            // Гарантуємо, що created_at буде або Carbon об'єктом, або null.
            // Це захищає від Undefined property.
            $newItem->created_at = $item->created_at; // Тут $item->created_at вже має бути Carbon або null

            return $newItem;
        });


        // dd(__METHOD__, $collection, $collection->toArray()); // Розкоментуйте для дебагу

        /*
        dd(
            get_class($collection),      // Клас базової колекції
            $collection                   // Вміст базової колекції
        );
        */

        // --- Демонстрація базових методів колекцій ---

        // first(): Отримує перший елемент колекції.
        $result['first'] = $collection->first();
        // last(): Отримує останній елемент колекції.
        $result['last'] = $collection->last();

        // where(): Фільтрує колекцію за заданими критеріями (поле, оператор, значення).
        // values(): Скидає ключі масиву після фільтрації, переіндексуючи їх з 0.
        // keyBy('id'): Прирівнюємо id з бд з ключем масива.
        $result['where']['data'] = $collection
            ->where('category_id', 10) // Припускаємо, що category_id є в початковій моделі BlogPost
            ->values()
            ->keyBy('item_id');        // Використовуємо item_id, оскільки ми перетворили колекцію

        $result['where']['count'] = $result['where']['data']->count();
        $result['where']['isEmpty'] = $result['where']['data']->isEmpty();
        $result['where']['isNotEmpty'] = $result['where']['data']->isNotEmpty();

        if ($result['where']['data']->isNotEmpty()) {
            //
        }

        // firstWhere(): Отримує перший елемент, який відповідає заданим критеріям.
        // Зверніть увагу: тут ми припускаємо, що created_at вже є об'єктом Carbon
        $result['first_where'] = $collection
            ->firstWhere('created_at', function ($value) {
                // Перевіряємо, що $value є об'єктом Carbon і дата більша за 2020-02-24 03:46:16
                return ($value instanceof Carbon) && $value->greaterThan(Carbon::parse('2020-02-24 03:46:16'));
            });

        // dd($result); // Закоментовано для нормальної роботи

        // --- Демонстрація методів додавання та видалення елементів ---

        $newItem = new \stdClass;
        $newItem->item_id = 9999; // Змінено на item_id
        $newItem->item_name = 'Новий елемент на початку'; // Додано для ясності
        $newItem->exists = true; // Додано для ясності
        $newItem->created_at = Carbon::now(); // Додано для ясності

        $newItem2 = new \stdClass;
        $newItem2->item_id = 8888; // Змінено на item_id
        $newItem2->item_name = 'Новий елемент в кінці'; // Додано для ясності
        $newItem2->exists = true; // Додано для ясності
        $newItem2->created_at = Carbon::now(); // Додано для ясності

        // dd($newItem, $newItem2); // Закоментовано для нормальної роботи

        $newItemFirst = $collection->prepend($newItem)->first();
        $newItemLast = $collection->push($newItem2)->last();
        // pull($key): Видаляє і повертає елемент за його ключем. ЗМІНЮЄ ОРИГІНАЛЬНУ колекцію.
        // Зверніть увагу: ключ 1 може не існувати після transform(), якщо колекція переіндексована
        // Краще використовувати keyBy('item_id') і потім pull(some_item_id)
        $pulledItem = $collection->pull(1); // Припускаємо, що елемент з ключем 1 існує

        // dd(compact('collection', 'newItemFirst', 'newItemLast', 'pulledItem')); // Закоментовано для нормальної роботи

        // --- Демонстрація фільтрації та сортування ---

        // filter(): Фільтрує колекцію, залишаючи елементи, для яких callback повертає true.
        $filtered = $collection->filter(function ($item) {
            // Тепер, оскільки ми перетворили колекцію раніше, $item->created_at
            // має бути або Carbon об'єктом, або null.
            if ($item->created_at === null || !($item->created_at instanceof Carbon)) {
                return false; // Пропускаємо елементи, де created_at не є дійсним об'єктом Carbon
            }

            // Рядок, де виникала помилка, тепер має працювати коректно.
            $byDay = $item->created_at->isFriday();
            // Враховуючи що сьогодні вівторок, 10 червня 2025,
            // $byDate = $item->created_at->day == 11; буде шукати 11 число
            $byDate = $item->created_at->day == 10; // Змінено для перевірки на поточний день

            $result = $byDay && $byDate;

            return $result;
        });

        // dd(compact('filtered')); // Закоментовано для нормальної роботи

        $sortedSimpleCollection = collect([5, 3, 1, 2, 4])->sort()->values();
        $sortedAscCollection = $collection->sortBy('created_at');
        $sortedDescCollection = $collection->sortByDesc('item_id');

        // dd(compact('sortedSimpleCollection', 'sortedAscCollection', 'sortedDescCollection')); // Закоментовано для нормальної роботи

        return response()->json(['message' => 'Колекції оброблені без помилок']);
    }
}
