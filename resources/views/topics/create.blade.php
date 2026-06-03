@extends('layouts.app')

@section('content')
    <h1>Создание темы</h1>

    <form action="{{ route('topics.store') }}" method="POST">
        @csrf

        <div>
            <label for="name">Название темы:</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
        </div>

        <div>
            <button type="submit">Сохранить</button>
            <a href="{{ route('topics.index') }}">Отмена</a>
        </div>
    </form>

    @if ($errors->any())
        <div>
            <strong>Ошибка!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection
