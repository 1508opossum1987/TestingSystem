@extends('layouts.app')

@section('content')
    <h1>Создание вопроса</h1>

    <form action="{{ route('questions.store') }}" method="POST">
        @csrf

        <div>
            <label for="topic_id">Тема:</label>
            <select name="topic_id" id="topic_id" required>
                <option value="">Выберите тему</option>
                @foreach($topics as $topic)
                    <option value="{{ $topic->id }}" {{ old('topic_id') == $topic->id ? 'selected' : '' }}>
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
                    <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>
                        {{ $level->question_level }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="question_text">Текст вопроса:</label>
            <textarea name="question_text" id="question_text" rows="3" cols="50" required>{{ old('question_text') }}</textarea>
        </div>

        <div>
            <label>Варианты ответов:</label>
            <div>
                <label>A: <input type="text" name="options[A]" value="{{ old('options.A') }}" required></label>
            </div>
            <div>
                <label>B: <input type="text" name="options[B]" value="{{ old('options.B') }}" required></label>
            </div>
            <div>
                <label>C: <input type="text" name="options[C]" value="{{ old('options.C') }}" required></label>
            </div>
            <div>
                <label>D: <input type="text" name="options[D]" value="{{ old('options.D') }}" required></label>
            </div>
        </div>

        <div>
            <label for="correct_answer">Правильный ответ:</label>
            <select name="correct_answer" id="correct_answer" required>
                <option value="">Выберите вариант</option>
                <option value="A" {{ old('correct_answer') == 'A' ? 'selected' : '' }}>A</option>
                <option value="B" {{ old('correct_answer') == 'B' ? 'selected' : '' }}>B</option>
                <option value="C" {{ old('correct_answer') == 'C' ? 'selected' : '' }}>C</option>
                <option value="D" {{ old('correct_answer') == 'D' ? 'selected' : '' }}>D</option>
            </select>
        </div>

        <div>
            <label for="type">Тип вопроса:</label>
            <select name="type" id="type" required>
                <option value="single_choice" {{ old('type') == 'single_choice' ? 'selected' : '' }}>Одиночный выбор</option>
            </select>
        </div>

        <div>
            <button type="submit">Сохранить</button>
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
