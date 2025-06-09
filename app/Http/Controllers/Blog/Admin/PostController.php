<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller; // Переконайтеся, що BaseController імпортований
use App\Repositories\BlogPostRepository;
use App\Repositories\BlogCategoryRepository; // ДОДАЄМО: Імпорт репозиторію категорій
use App\Http\Requests\BlogPostUpdateRequest; // ДОДАЄМО: Імпорт Form Request для оновлення
use Carbon\Carbon; // ДОДАЄМО: Імпорт класу Carbon для роботи з датами
use Illuminate\Support\Str; // ДОДАЄМО: Імпорт хелпера Str для генерації slug
use Illuminate\Http\Request; // Можливо, вам знадобиться цей use, якщо метод store() використовує Request

class PostController extends BaseController
{
    /**
     * @var BlogPostRepository
     */
    private $blogPostRepository;

    /**
     * @var BlogCategoryRepository
     */
    private $blogCategoryRepository; // ДОДАЄМО: Властивість для репозиторію категорій

    public function __construct()
    {
        parent::__construct();

        $this->blogPostRepository = app(BlogPostRepository::class);
        $this->blogCategoryRepository = app(BlogCategoryRepository::class); // ДОДАЄМО: Ініціалізація репозиторію категорій
    }

    /**
     * Display a listing of the resource.
     * (Цей метод залишається незмінним, він відображає список постів)
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = $this->blogPostRepository->getPaginatedList(20);

        return view('blog.admin.posts.index', compact('paginator'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Отримуємо модель посту за ID для редагування
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) {
            // Якщо пост не знайдено, перериваємо запит з помилкою 404
            abort(404);
        }

        // Отримуємо список категорій для випадаючого списку (ComboBox)
        $categoryList = $this->blogCategoryRepository->getForComboBox();

        // Повертаємо представлення з даними про пост та списком категорій
        return view('blog.admin.posts.edit', compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BlogPostUpdateRequest  $request // ЗМІНА ТИПУ: Тепер використовуємо наш Form Request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogPostUpdateRequest $request, $id)
    {
        // Знаходимо пост за ID
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) { // Якщо ID не знайдено
            return back() // Перенаправляємо назад на попередню сторінку
            ->withErrors(['msg' => "Запис id=[{$id}] не знайдено"]) // Видаємо повідомлення про помилку
            ->withInput(); // Повертаємо введені користувачем дані, щоб вони не зникли
        }

        // Отримуємо всі дані з форми, які пройшли валідацію
        $data = $request->all();

        // Якщо поле 'slug' (псевдонім) порожнє, генеруємо його з 'title'
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Якщо поле 'published_at' порожнє І пост має бути опублікований (is_published == 1)
        if (empty($item->published_at) && $data['is_published']) {
            $data['published_at'] = Carbon::now(); // Встановлюємо поточну дату та час
        }

        // Оновлюємо дані моделі посту та зберігаємо їх у базі даних
        $result = $item->update($data);

        if ($result) {
            // Якщо оновлення успішне, перенаправляємо на сторінку редагування з повідомленням про успіх
            return redirect()
                ->route('blog.admin.posts.edit', $item->id)
                ->with(['success' => 'Успішно збережено']);
        } else {
            // Якщо сталася помилка при збереженні, перенаправляємо назад з повідомленням про помилку
            return back()
                ->withErrors(['msg' => 'Помилка збереження']) // Використовуємо withErrors для відображення помилки
                ->withInput();
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
