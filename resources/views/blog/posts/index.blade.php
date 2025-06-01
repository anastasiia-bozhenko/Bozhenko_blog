@extends('layouts.main') {{-- Вказуємо, що цей шаблон розширює layouts.main --}}

@section('content') {{-- Позначаємо секцію, яка буде вставлена в @yield('content') --}}
<div class="container mt-4"> {{-- Додаємо контейнер для центрування та відступів --}}
    <div class="row">
        <div class="col-md-12"> {{-- Використовуємо одну повну колонку для обох елементів --}}
            <h1 class="mb-3">Список постів блогу</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12"> {{-- Таблиця тепер у своїй власній повній колонці --}}
            <table class="table table-bordered table-hover"> {{-- Додаємо Bootstrap класи для таблиці --}}
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Заголовок</th>
                    <th>Дата створення</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->title}}</td>
                        <td>{{$item->created_at}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
