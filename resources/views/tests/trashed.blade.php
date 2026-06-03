@extends('layouts.app')

@section('content')
    <h1>Корзина (удалённые тесты)</h1>

    <a href="{{ route('tests.index') }}">← Назад к списку</a>

    @if(session('success'))
        <div style="color: green; margin-top: 10px;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red; margin-top: 10px;">{{ session('error') }}</div>
    @endif

    @if($tests->isEmpty())
        <p>Корзина пуста. Нет удалённых тестов.</p>
    @else
        <table border="1" cellpadding="8" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>ID</th>
                <th>Тема</th>
                <th>Уровень</th>
                <th>Кол-во вопросов</th>
                <th>Дата удаления</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tests as $test)
                <tr>
                    <td>{{ $test->id }}</td>
                    <td>{{ $test->topic->name ?? '—' }}</td>
                    <td>{{ $test->question_level->question_level ?? '—' }}</td>
                    <td>{{ $test->question_count }}</td>
                    <td>{{ $test->deleted_at }}</td>
                    <td>
                        <form action="{{ route('tests.restore', $test->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit">Восстановить</button>
                        </form>

                        <form action="{{ route('tests.forceDestroy', $test->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Удалить навсегда?')">Удалить навсегда</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endsection
