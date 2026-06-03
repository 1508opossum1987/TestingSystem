@extends('layouts.app')

@section('content')
    <h1>Просмотр теста</h1>

    <div>
        <strong>ID:</strong> {{ $test->id }}
    </div>
    <div>
        <strong>Тема:</strong> {{ $test->topic->name ?? 'Не указана' }}
    </div>
    <div>
        <strong>Уровень сложности:</strong> {{ $test->question_level->question_level ?? 'Не указан' }}
    </div>
    <div>
        <strong>Количество вопросов:</strong> {{ $test->question_count }}
    </div>
    <div>
        <strong>Дата создания:</strong> {{ $test->created_at }}
    </div>
    <div>
        <strong>Дата обновления:</strong> {{ $test->updated_at }}
    </div>

    <div style="margin-top: 15px;">
        <a href="{{ route('tests.edit', $test) }}">Редактировать</a> |
        <a href="{{ route('tests.index') }}">Назад к списку</a>
    </div>
@endsection
