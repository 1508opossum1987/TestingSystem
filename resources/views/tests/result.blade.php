@extends('layouts.app')

@section('content')
    <h1>Результат теста</h1>

    <div style="margin-bottom: 20px;">
        <strong>Тест:</strong> {{ $test->topic->name }} ({{ $test->question_level->question_level }} класс)
    </div>

    <div style="border: 1px solid #ccc; padding: 20px; margin-top: 20px;">
        <div><strong>Результат:</strong> {{ $result->score_percent }}%</div>
        <div><strong>Оценка:</strong> {{ $result->grade }}</div>
        <div><strong>Дата прохождения:</strong> {{ $result->created_at }}</div>
    </div>

    @if($details)
        <div style="margin-top: 20px;">
            <h3>Детали ответов:</h3>
            <table border="1" cellpadding="8" style="width: 100%;">
                <thead>
                <tr>
                    <th>Вопрос</th>
                    <th>Ваш ответ</th>
                    <th>Правильный ответ</th>
                    <th>Результат</th>
                </tr>
                </thead>
                <tbody>
                @foreach($details['answers'] as $questionId => $answer)
                    <tr>
                        <td>{{ $answer['question_text'] ?? 'Вопрос ' . $questionId }}</td>
                        <td>{{ $answer['user_answer'] ?? '—' }}</td>
                        <td>{{ $answer['correct_answer'] ?? '—' }}</td>
                        <td style="color: {{ $answer['is_correct'] ? 'green' : 'red' }}">
                            {{ $answer['is_correct'] ? '✓ Правильно' : '✗ Неправильно' }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div style="margin-top: 20px;">
        <a href="{{ route('tests.index') }}">← К списку тестов</a>
    </div>
@endsection
