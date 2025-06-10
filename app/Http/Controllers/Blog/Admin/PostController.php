<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller; // Переконайтеся, що BaseController імпортований
use App\Models\BlogPost;
use App\Repositories\BlogPostRepository;
use App\Repositories\BlogCategoryRepository; // ДОДАЄМО: Імпорт репозиторію категорій
use App\Http\Requests\BlogPostUpdateRequest; // ДОДАЄМО: Імпорт Form Request для оновлення
use Carbon\Carbon; // ДОДАЄМО: Імпорт класу Carbon для роботи з датами
use Illuminate\Support\Str; // ДОДАЄМО: Імпорт хелпера Str для генерації slug
use Illuminate\Http\Request; // Можливо, вам знадобиться цей use, якщо метод store() використовує Request
use App\Jobs\BlogPostAfterCreateJob;
use App\Jobs\BlogPostAfterDeleteJob;
use App\Http\Requests\BlogPostCreateRequest;
use Illuminate\Foundation\Bus\DispatchesJobs;

class PostController extends BaseController
{
    use DispatchesJobs;
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
        $paginator = $this->blogPostRepository->getAllWithPaginate(20);

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
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) {
            return back()
                ->withErrors(['msg' => "Запис id=[{$id}] не знайдено"])
                ->withInput();
        }

        $data = $request->all();
        $item->fill($data);
        $result = $item->save();

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
        $item = new BlogPost(); // Створюємо новий, порожній екземпляр моделі BlogPost

        // Отримуємо список категорій для випадаючого списку
        $categoryList = $this->blogCategoryRepository->getForComboBox();

        // Повертаємо представлення 'blog.admin.posts.edit',
        // передаючи порожній $item та $categoryList.
        // Шаблон 'edit' буде обробляти це як створення нового посту,
        // оскільки $item->exists буде false.
        return view('blog.admin.posts.edit', compact('item', 'categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogPostCreateRequest $request) // Припустимо, що у вас є BlogCategoryCreateRequest
    {
        $data = $request->input();
        // ... (ваша логіка для створення, якщо вона є)

        // Важливо: переконайтеся, що $item створюється ДО відправлення Job
        $item = new \App\Models\BlogPost(); // Створюємо новий екземпляр моделі
        $item->fill($data); // Заповнюємо даними з запиту
        $item->user_id = auth()->id() ?? 1; // Припустимо, що user_id встановлюється тут
        $item->category_id = $data['category_id'] ?? 1; // Припустимо, що category_id встановлюється тут

        if (empty($item->slug)) {
            $item->slug = Str::slug($item->title);
        }
        if (empty($item->published_at) && $item->is_published) {
            $item->published_at = Carbon::now();
        }

        $result = $item->save(); // Зберігаємо пост у базі даних


        if ($result) {
            // ДОДАЄМО ЦЕЙ КОД: Відправляємо завдання в чергу
            $job = new BlogPostAfterCreateJob($item); // Передаємо об'єкт BlogPost
            $this->dispatch($job); // Або dispatch($job);

            return redirect()
                ->route('blog.admin.posts.edit', $item->id)
                ->with(['success' => 'Успішно створено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Помилка створення'])
                ->withInput();
        }
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
    public function destroy($id)
    {
        // Знайдіть пост для видалення (або просто ID, якщо логіка дозволяє)
        $item = $this->blogPostRepository->getEdit($id);

        if (empty($item)) {
            return back()
                ->withErrors(['msg' => "Запис id=[{$id}] не знайдено для видалення"])
                ->withInput();
        }

        // Виконайте видалення (м'яке або жорстке)
        $result = $item->delete(); // Використовуємо delete() для SoftDeletes

        if ($result) {
            // ДОДАЄМО ЦЕЙ КОД: Відправляємо завдання в чергу з затримкою
            BlogPostAfterDeleteJob::dispatch($id)->delay(Carbon::now()->addSeconds(20)); // Затримка на 20 секунд

            return redirect()
                ->route('blog.admin.posts.index') // Перенаправляємо на список постів
                ->with(['success' => 'Успішно видалено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Помилка видалення'])
                ->withInput();
        }
    }
}
