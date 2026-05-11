@extends('layouts.app')

@section('content')
    <h1>Список результатов</h1>

    <table border="1" cellpadding="8">
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
                <td>{{ $result->user->name }}</td>
                <td>{{ $result->test->topic->name }} ({{ $result->test->questionLevel->level }} кл.)</td>
                <td>{{ $result->score_percent }}%</td>
                <td>{{ $result->grade }}</td>
                <td>{{ $result->created_at }}</td>
                <td>
                    <button>Просмотр</button>
                    <button>Удалить</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
