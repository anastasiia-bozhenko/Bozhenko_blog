@extends('layouts.app') {{-- Переконайтеся, що тут правильний базовий макет, який ви використовуєте (раніше був layouts.main, змініть на layouts.app, якщо ви його використовували для категорій) --}}

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
                    <a href="{{ route('blog.admin.posts.create') }}" class="btn btn-primary">Додати</a>
                </nav>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover mx-auto w-auto">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Автор</th>
                                <th>Категорія</th>
                                <th>Заголовок</th>
                                <th>Дата публікації</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($paginator as $post)
                                @php /** @var \App\Models\BlogPost $post */ @endphp
                                <tr @if (!$post->is_published) style="background-color: #ccc;" @endif>
                                    <td>{{ $post->id }}</td>
                                    <td>{{ $post->user_id }}</td> {{-- Надалі замінимо на ім'я користувача --}}
                                    <td>{{ $post->category_id }}</td> {{-- Надалі замінимо на назву категорії --}}
                                    <td>
                                        <a href="{{ route('blog.admin.posts.edit', $post->id) }}">
                                            {{ $post->title }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d.M H:i') : '' }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if($paginator->total() > $paginator->count())
            <br>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            {{ $paginator->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
