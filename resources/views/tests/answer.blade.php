@extends('layouts.app')

@section('content')
    <h1>{{ $test->topic->name }} ({{ $test->question_level->question_level }} класс)</h1>

    <div style="margin-bottom: 20px;">
        <strong>Прогресс:</strong> {{ $answeredCount }} из {{ $totalCount }} вопросов отвечено
    </div>

    <div style="margin-bottom: 20px;">
        <a href="{{ route('tests.answer', $test) }}">Обновить</a>
    </div>

    <div style="border: 1px solid #ccc; padding: 20px; margin-top: 20px;">
        <h3>Вопрос:</h3>
        <p>{{ $question->question_text }}</p>

        <form action="{{ route('tests.saveAnswer', $test) }}" method="POST">
            @csrf
            <input type="hidden" name="question_id" value="{{ $question->id }}">
            <input type="hidden" name="next" value="1">

            <div style="margin: 15px 0;">
                @foreach($options as $key => $value)
                    <div style="margin: 5px 0;">
                        <label>
                            <input type="radio" name="answer" value="{{ $key }}"
                                   {{ $currentAnswer == $key ? 'checked' : '' }} required>
                            <strong>{{ $key }}.</strong> {{ $value }}
                        </label>
                    </div>
                @endforeach
            </div>

            <button type="submit">Ответить и продолжить</button>
        </form>
    </div>

    <div style="margin-top: 20px;">
        <a href="{{ route('tests.show', $test) }}">Отменить и вернуться</a>
    </div>
@endsection
