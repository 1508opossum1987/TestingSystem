@extends('layouts.app')

@section('content')
    <h1>Просмотр вопроса</h1>

    <div>
        <strong>ID:</strong> {{ $question->id }}
    </div>
    <div>
        <strong>Тема:</strong> {{ $question->topic->name ?? 'Не указана' }}
    </div>
    <div>
        <strong>Уровень сложности:</strong> {{ $question->question_level->question_level ?? 'Не указан' }}
    </div>
    <div>
        <strong>Текст вопроса:</strong> {{ $question->question_text }}
    </div>
    <div>
        <strong>Варианты ответов:</strong>
        @php
            $options = is_string($question->options) ? json_decode($question->options, true) : $question->options;
        @endphp
        <ul>
            <li><strong>A:</strong> {{ $options['A'] ?? '' }}</li>
            <li><strong>B:</strong> {{ $options['B'] ?? '' }}</li>
            <li><strong>C:</strong> {{ $options['C'] ?? '' }}</li>
            <li><strong>D:</strong> {{ $options['D'] ?? '' }}</li>
        </ul>
    </div>
    <div>
        <strong>Правильный ответ:</strong> {{ $question->correct_answer }}
    </div>
    <div>
        <strong>Тип вопроса:</strong>
        @if($question->type == 'single_choice') Одиночный выбор @endif
    </div>
    <div>
        <strong>Дата создания:</strong> {{ $question->created_at }}
    </div>
    <div>
        <strong>Дата обновления:</strong> {{ $question->updated_at }}
    </div>

    <div style="margin-top: 15px;">
        <a href="{{ route('questions.edit', $question) }}">Редактировать</a> |
        <a href="{{ route('questions.index') }}">Назад к списку</a>
    </div>
@endsection
