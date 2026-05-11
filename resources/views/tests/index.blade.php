@extends('layouts.app')

@section('content')
    <h1>Список тестов</h1>

    <button>Создать тест</button>

    <table border="1" cellpadding="8">
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
                <td>{{ $test->topic->name }}</td>
                <td>{{ $test->questionLevel->level }}</td>
                <td>{{ $test->question_count }}</td>
                <td>
                    <button>Просмотр</button>
                    <button>Редактировать</button>
                    <button>Удалить</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
