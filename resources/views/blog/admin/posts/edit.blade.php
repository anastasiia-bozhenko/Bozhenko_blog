@extends('layouts.main') {{-- Увага: в інструкції для цього файлу вказано layouts.main --}}

@section('content')
    @php /** @var \App\Models\BlogPost $item */ @endphp
    <div class="container">
        {{-- Включаємо partial для відображення повідомлень про успіх/помилки --}}
        @include('blog.admin.posts.includes.result_messages')

        {{-- Форма для оновлення або створення посту --}}
        @if ($item->exists) {{-- Якщо пост вже існує, це форма для оновлення --}}
        <form method="POST" action="{{ route('blog.admin.posts.update', $item->id) }}">
            @method('PATCH') {{-- Метод PATCH використовується для оновлення ресурсу --}}
            @else {{-- Якщо пост не існує, це форма для створення --}}
            <form method="POST" action="{{ route('blog.admin.posts.store') }}">
                @endif
                @csrf {{-- CSRF токен для захисту від міжсайтових підробок запитів --}}

                <div class="row justify-content-center">
                    <div class="col-md-8">
                        {{-- Включаємо partial для основної колонки форми (заголовок, текст) --}}
                        @include('blog.admin.posts.includes.post_edit_main_col')
                    </div>
                    <div class="col-md-3">
                        {{-- Включаємо partial для додаткової колонки форми (категорія, slug, публікація) --}}
                        @include('blog.admin.posts.includes.post_edit_add_col')
                    </div>
                </div>
            </form>

            {{-- Форма для видалення посту (відображається тільки якщо пост вже існує) --}}
            @if ($item->exists)
                <br>
                <form method="POST" action="{{ route('blog.admin.posts.destroy', $item->id) }}">
                    @method('DELETE') {{-- Метод DELETE використовується для видалення ресурсу --}}
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card card-block">
                                <div class="card-body ml-auto">
                                    <button type="submit" class="btn btn-link">Видалити</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </form>
        @endif
    </div>
@endsection
