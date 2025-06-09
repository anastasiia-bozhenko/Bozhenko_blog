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
        if (empty($item)) {
            return back()
                ->withErrors(['msg' => "Запис id=[{$id}] не знайдено"])
                ->withInput();
        }

        $data = $request->all();

        // Логіка для генерації slug та published_at тепер в BlogPostObserver

        // Оновлюємо властивості моделі з даних, отриманих із запиту
        // IMPORTANT: оскільки Observer буде працювати з моделлю $item,
        // ми повинні оновити її властивості перед викликом update()
        $item->fill($data); // Заповнюємо модель даними, щоб Observer міг з ними працювати

        $result = $item->save(); // Викликаємо save() замість update($data), щоб запустити події Observer

        if ($result) {
            return redirect()
                ->route('blog.admin.posts.edit', $item->id)
                ->with(['success' => 'Успішно збережено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Помилка збереження'])
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
