@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif

    <h1>Список тем</h1>

    <a href="{{ route('topics.create') }}">
        <button>Создать тему</button>
    </a>

    <table border="1" cellpadding="8">
        <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($topics as $topic)
            <tr>
                <td>{{ $topic->id }}</td>
                <td>{{ $topic->name }}</td>
                <td>
                    <a href="{{ route('topics.show', $topic) }}">
                        <button>Просмотр</button>
                    </a>
                    <a href="{{ route('topics.edit', $topic) }}">
                        <button>Редактировать</button>
                    </a>

                    <form action="{{ route('topics.destroy', $topic) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Вы уверены?')">Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
        <a href="{{ route('topics.trashed') }}">Корзина</a>
        </tbody>
@endsection
