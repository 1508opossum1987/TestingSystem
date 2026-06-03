@extends('layouts.app')

@section('content')
    <h1>Создание теста</h1>

    <form action="{{ route('tests.store') }}" method="POST">
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
            <label for="question_count">Количество вопросов:</label>
            <input type="number" name="question_count" id="question_count" value="{{ old('question_count', 10) }}" min="1" max="50" required>
        </div>

        <div>
            <button type="submit">Сохранить</button>
            <a href="{{ route('tests.index') }}">Отмена</a>
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
