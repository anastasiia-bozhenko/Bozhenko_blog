@php /** @var \Illuminate\Support\MessageBag $errors */ @endphp
{{-- Перевіряємо, чи є якісь помилки валідації --}}
@if ($errors->any())
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{-- Відображаємо тільки перше повідомлення про помилку --}}
                {{ $errors->first() }}
            </div>
        </div>
    </div>
@endif

{{-- Перевіряємо, чи є повідомлення про успіх у сесії --}}
@if (session('success'))
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{-- Відображаємо повідомлення про успіх --}}
                {{ session()->get('success') }}
            </div>
        </div>
    </div>
@endif
