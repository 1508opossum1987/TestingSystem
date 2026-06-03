@extends('layouts.app')

@section('content')
    <h1>Просмотр результата</h1>

    <div>
        <strong>ID:</strong> {{ $result->id }}
    </div>
    <div>
        <strong>Пользователь:</strong> {{ $result->user->name ?? 'Не указан' }}
    </div>
    <div>
        <strong>Тест:</strong> {{ $result->test->topic->name ?? '—' }} ({{ $result->test->question_level->question_level ?? '—' }} кл.)
    </div>
    <div>
        <strong>Результат (%):</strong> {{ $result->score_percent }}%
    </div>
    <div>
        <strong>Оценка:</strong> {{ $result->grade ?? '—' }}
    </div>
    <div>
        <strong>Ответы:</strong>
        <pre>{{ is_string($result->answers) ? json_decode($result->answers, true) : $result->answers }}</pre>
    </div>
    <div>
        <strong>Дата прохождения:</strong> {{ $result->created_at }}
    </div>

    <div style="margin-top: 15px;">
        <a href="{{ route('results.index') }}">Назад к списку</a>
    </div>
@endsection
