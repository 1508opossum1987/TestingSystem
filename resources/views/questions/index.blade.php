@extends('layouts.app')

@section('content')
    <h1>Список вопросов</h1>

    @if(session('success'))
        <div style="color: green; margin-bottom: 10px;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red; margin-bottom: 10px;">{{ session('error') }}</div>
    @endif

    <a href="{{ route('questions.create') }}"><button>Создать вопрос</button></a>
    <a href="{{ route('questions.trashed') }}" style="margin-left: 10px;"><button>Корзина</button></a>

    <table border="1" cellpadding="8" style="margin-top: 15px;">
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
                <td>{{ $question->topic->name ?? '—' }}</td>
                <td>{{ $question->question_level->question_level ?? '—' }}</td>
                <td>{{ Str::limit($question->question_text, 50) }}</td>
                <td>
                    <a href="{{ route('questions.show', $question) }}"><button>Просмотр</button></a>
                    <a href="{{ route('questions.edit', $question) }}"><button>Редактировать</button></a>

                    <form action="{{ route('questions.destroy', $question) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Вы уверены?')">Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
@endsection
