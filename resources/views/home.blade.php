@extends('layouts.app')

@section('content')
    <h1>Доступные тесты</h1>

    <table border="1" cellpadding="8">
        <thead>
        <tr>
            <th>Тема</th>
            <th>Уровень (класс)</th>
            <th>Кол-во вопросов</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tests as $test)
            <tr>
                <td>{{ $test->topic->name }}</td>
                <td>{{ $test->question_level->level }}</td>
                <td>{{ $test->question_count }}</td>
                <td>
                    <button>Начать тест</button>
                    <button>Подробнее</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
