@extends('layouts.app')

@section('content')
    <h1>{{ $test->topic->name }} ({{ $test->question_level->question_level }} класс)</h1>

    <div style="margin-bottom: 20px;">
        <strong>Вы ответили на все {{ count($answers) }} вопросов!</strong>
    </div>

    <div style="border: 1px solid #ccc; padding: 20px; margin-top: 20px;">
        <p>Вы ответили на все вопросы. Нажмите кнопку "Завершить тест", чтобы получить результат.</p>

        <form action="{{ route('tests.complete', $test) }}" method="POST">
            @csrf
            <button type="submit">Завершить тест</button>
        </form>
    </div>

    <div style="margin-top: 20px;">
        <a href="{{ route('tests.show', $test) }}">Отменить и вернуться</a>
    </div>
@endsection
