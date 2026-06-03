@extends('layouts.app')

@section('content')
    <h1>Редактирование вопроса</h1>

    <form action="{{ route('questions.update', $question) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="topic_id">Тема:</label>
            <select name="topic_id" id="topic_id" required>
                <option value="">Выберите тему</option>
                @foreach($topics as $topic)
                    <option value="{{ $topic->id }}" {{ old('topic_id', $question->topic_id) == $topic->id ? 'selected' : '' }}>
                        {{ $topic->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="level_id">Уровень сложности:</label>
            <select name="level_id" id="level_id" required>
                <option value="">Выберите уровень</option>
                @foreach($levels as $level)
                    <option value="{{ $level->id }}" {{ old('level_id', $question->level_id) == $level->id ? 'selected' : '' }}>
                        {{ $level->question_level }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="question_text">Текст вопроса:</label>
            <textarea name="question_text" id="question_text" rows="3" cols="50" required>{{ old('question_text', $question->question_text) }}</textarea>
        </div>

        @php
            $options = is_string($question->options) ? json_decode($question->options, true) : $question->options;
        @endphp

        <div>
            <label>Варианты ответов:</label>
            <div>
                <label>A: <input type="text" name="options[A]" value="{{ old('options.A', $options['A'] ?? '') }}" required></label>
            </div>
            <div>
                <label>B: <input type="text" name="options[B]" value="{{ old('options.B', $options['B'] ?? '') }}" required></label>
            </div>
            <div>
                <label>C: <input type="text" name="options[C]" value="{{ old('options.C', $options['C'] ?? '') }}" required></label>
            </div>
            <div>
                <label>D: <input type="text" name="options[D]" value="{{ old('options.D', $options['D'] ?? '') }}" required></label>
            </div>
        </div>

        <div>
            <label for="correct_answer">Правильный ответ:</label>
            <select name="correct_answer" id="correct_answer" required>
                <option value="">Выберите вариант</option>
                <option value="A" {{ old('correct_answer', $question->correct_answer) == 'A' ? 'selected' : '' }}>A</option>
                <option value="B" {{ old('correct_answer', $question->correct_answer) == 'B' ? 'selected' : '' }}>B</option>
                <option value="C" {{ old('correct_answer', $question->correct_answer) == 'C' ? 'selected' : '' }}>C</option>
                <option value="D" {{ old('correct_answer', $question->correct_answer) == 'D' ? 'selected' : '' }}>D</option>
            </select>
        </div>

        <div>
            <label for="type">Тип вопроса:</label>
            <select name="type" id="type" required>
                <option value="single_choice" {{ old('type', $question->type) == 'single_choice' ? 'selected' : '' }}>Одиночный выбор</option>
            </select>
        </div>

        <div>
            <button type="submit">Обновить</button>
            <a href="{{ route('questions.index') }}">Отмена</a>
        </div>
    </form>

    @if ($errors->any())
        <div style="color: red; margin-top: 15px;">
            <strong>Ошибка!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection
