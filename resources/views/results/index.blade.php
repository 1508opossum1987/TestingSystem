@extends('layouts.app')

@section('content')
    <h1>Список результатов</h1>

    @if(session('success'))
        <div style="color: green; margin-bottom: 10px;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red; margin-bottom: 10px;">{{ session('error') }}</div>
    @endif

    <a href="{{ route('results.trashed') }}" style="margin-left: 10px;"><button>Корзина</button></a>

    <table border="1" cellpadding="8" style="margin-top: 15px;">
        <thead>
        <tr>
            <th>ID</th>
            <th>Пользователь</th>
            <th>Тест</th>
            <th>Результат (%)</th>
            <th>Оценка</th>
            <th>Дата</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($results as $result)
            <tr>
                <td>{{ $result->id }}</td>
                <td>{{ $result->user->name ?? '—' }}</td>
                <td>{{ $result->test->topic->name ?? '—' }} ({{ $result->test->question_level->question_level ?? '—' }} кл.)</td>
                <td>{{ $result->score_percent }}%</td>
                <td>{{ $result->grade ?? '—' }}</td>
                <td>{{ $result->created_at }}</td>
                <td>
                    <a href="{{ route('results.show', $result) }}"><button>Просмотр</button></a>

                    <form action="{{ route('results.destroy', $result) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Вы уверены?')">Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
@endsection
