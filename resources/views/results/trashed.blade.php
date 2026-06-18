@extends('layouts.app')

@section('content')
    <h1>Корзина (удалённые результаты)</h1>

    <a href="{{ route('results.index') }}">← Назад к списку</a>

    @if(session('success'))
        <div style="color: green; margin-top: 10px;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red; margin-top: 10px;">{{ session('error') }}</div>
    @endif

    @if($results->isEmpty())
        <p>Корзина пуста. Нет удалённых результатов.</p>
    @else
        <table border="1" cellpadding="8" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>ID</th>
                <th>Пользователь</th>
                <th>Тест</th>
                <th>Результат (%)</th>
                <th>Дата удаления</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($results as $result)
                <tr>
                    <td>{{ $result->id }}</td>
                    <td>{{ $result->user->name ?? '—' }}</td>
                    <td>{{ $result->test->topic->name ?? '—' }}</td>
                    <td>{{ $result->score_percent }}%</td>
                    <td>{{ $result->deleted_at }}</td>
                    <td>
                        <form action="{{ route('results.restore', $result->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit">Восстановить</button>
                        </form>

                        <form action="{{ route('results.forceDestroy', $result->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Удалить навсегда?')">Удалить навсегда</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div style="margin-top: 15px;">
            {{ $results->links() }}
        </div>
    @endif
@endsection
