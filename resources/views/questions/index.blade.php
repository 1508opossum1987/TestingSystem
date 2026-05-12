@extends('layouts.app')

@section('content')
    <h1>Список вопросов</h1>

    <button>Создать вопрос</button>

    <table border="1" cellpadding="8">
        <thead>
        <tr>
            <th>ID</th>
            <th>Тема</th>
            <th>Уровень</th>
            <th>Текст вопроса</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($questions as $question)
            <tr>
                <td>{{ $question->id }}</td>
                <td>{{ $question->topic->name }}</td>
                <td>{{ $question->question_level->level }}</td>
                <td>{{ $question->question_text }}</td>
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
