@extends('layouts.app')

@section('content')
    <h1>Список тестов</h1>

    @if(session('success'))
        <div style="color: green; margin-bottom: 10px;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red; margin-bottom: 10px;">{{ session('error') }}</div>
    @endif

    <a href="{{ route('tests.create') }}"><button>Создать тест</button></a>
    <a href="{{ route('tests.trashed') }}" style="margin-left: 10px;"><button>Корзина</button></a>

    <table border="1" cellpadding="8" style="margin-top: 15px;">
        <thead>
        <tr>
            <th>ID</th>
            <th>Тема</th>
            <th>Уровень</th>
            <th>Кол-во вопросов</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tests as $test)
            <tr>
                <td>{{ $test->id }}</td>
                <td>{{ $test->topic->name ?? '—' }}</td>
                <td>{{ $test->question_level->question_level ?? '—' }}</td>
                <td>{{ $test->question_count }}</td>
                <td>
                    <a href="{{ route('tests.show', $test) }}"><button>Просмотр</button></a>
                    <a href="{{ route('tests.edit', $test) }}"><button>Редактировать</button></a>

                    <form action="{{ route('tests.destroy', $test) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Вы уверены?')">Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
@endsection
