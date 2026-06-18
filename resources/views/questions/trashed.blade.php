@extends('layouts.app')

@section('content')
    <h1>Корзина (удалённые вопросы)</h1>

    <a href="{{ route('questions.index') }}">← Назад к списку</a>

    @if(session('success'))
        <div style="color: green; margin-top: 10px;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red; margin-top: 10px;">{{ session('error') }}</div>
    @endif

    @if($questions->isEmpty())
        <p>Корзина пуста. Нет удалённых вопросов.</p>
    @else
        <table border="1" cellpadding="8" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>ID</th>
                <th>Текст вопроса</th>
                <th>Дата удаления</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($questions as $question)
                <tr>
                    <td>{{ $question->id }}</td>
                    <td>{{ Str::limit($question->question_text, 50) }}</td>
                    <td>{{ $question->deleted_at }}</td>
                    <td>
                        <form action="{{ route('questions.restore', $question->id) }}" method="POST"
                              style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit">Восстановить</button>
                        </form>

                        <form action="{{ route('questions.forceDestroy', $question->id) }}" method="POST"
                              style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Удалить навсегда? Это действие не обратимо.')">Удалить
                                навсегда
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div style="margin-top: 15px;">
            {{ $questions->links() }}
        </div>
    @endif
@endsection
