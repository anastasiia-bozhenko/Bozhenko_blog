<?php

namespace App\Http\Controllers\Blog\Admin;

//use App\Http\Controllers\Controller; // Закоментувати цей рядок
use App\Http\Controllers\Blog\Admin\BaseController;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
//use Illuminate\Http\Request;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Http\Requests\BlogCategoryCreateRequest;
use App\Repositories\BlogCategoryRepository;


class CategoryController extends BaseController // Замінити на extends BaseController
{
    /**
     * @var BlogCategoryRepository
     */
    private $blogCategoryRepository;

    public function __construct()
    {
        parent::__construct(); // Викликаємо конструктор батьківського класу (BaseController)

        // Ініціалізуємо репозиторій через Service Container
        $this->blogCategoryRepository = app(BlogCategoryRepository::class);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $paginator = BlogCategory::paginate(5); // Закоментовано
        $paginator = $this->blogCategoryRepository->getAllWithPaginate(5); // Використовуємо репозиторій

        return view('blog.admin.categories.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $item = new BlogCategory();
        // $categoryList = BlogCategory::all(); // Закоментовано
        $categoryList = $this->blogCategoryRepository->getForComboBox(); // Використовуємо репозиторій

        return view('blog.admin.categories.edit', compact('item', 'categoryList'));
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // dd(__METHOD__); // Залишити закоментованим, оскільки ми його не використовуємо в роутах
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $item = $this->blogCategoryRepository->getEdit($id); // Отримуємо елемент через репозиторій
        if (empty($item)) { // якщо репозиторій не знайде наш id
            abort(404); // Генеруємо 404 помилку
        }
        // Передаємо $item->parent_id до getForComboBox, хоча в поточній реалізації
        // getForComboBox не використовує цей параметр. Можливо, для майбутньої фільтрації.
        $categoryList = $this->blogCategoryRepository->getForComboBox($item->parent_id);

        return view('blog.admin.categories.edit', compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param  BlogCategoryCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();

        // ВИДАЛЯЄМО ЦЕЙ КОД:
        // if (empty($data['slug'])) {
        //     $data['slug'] = Str::slug($data['title']);
        // }

        $item = (new Model())->create($data); // Створення моделі. Observer буде викликаний

        if ($item) {
            return redirect()
                ->route('blog.admin.categories.edit', $item->id)
                ->with(['success' => 'Успішно збережено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Помилка збереження'])
                ->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BlogCategoryUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogCategoryUpdateRequest $request, $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);
        if (empty($item)) {
            return back()
                ->withErrors(['msg' => "Запис id=[{$id}] не знайдено"])
                ->withInput();
        }

        $data = $request->all();

        // ВИДАЛЯЄМО ЦЕЙ КОД:
        // if (empty($data['slug'])) {
        //     $data['slug'] = Str::slug($data['title']);
        // }

        // IMPORTANT: оскільки Observer буде працювати з моделлю $item,
        // ми повинні оновити її властивості перед викликом save()
        $item->fill($data); // Заповнюємо модель даними

        $result = $item->save(); // Викликаємо save() для запуску подій Observer

        if ($result) {
            return redirect()
                ->route('blog.admin.categories.edit', $item->id)
                ->with(['success' => 'Успішно збережено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Помилка збереження'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // dd(__METHOD__); // Залишити закоментованим, оскільки ми його не використовуємо в роутах
    }
}
