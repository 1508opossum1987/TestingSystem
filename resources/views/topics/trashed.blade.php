@extends('layouts.app')

@section('content')
    <h1>Корзина (удалённые темы)</h1>

    <a href="{{ route('topics.index') }}">← Назад к списку тем</a>

    @if(session('success'))
        <div style="color: green; margin-top: 10px;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red; margin-top: 10px;">{{ session('error') }}</div>
    @endif

    @if($topics->isEmpty())
        <p>Корзина пуста. Нет удалённых тем.</p>
    @else
        <table border="1" cellpadding="8" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Дата удаления</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($topics as $topic)
                <tr>
                    <td>{{ $topic->id }}</td>
                    <td>{{ $topic->name }}</td>
                    <td>{{ $topic->deleted_at }}</td>
                    <td>
                        <form action="{{ route('topics.restore', $topic->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit">Восстановить</button>
                        </form>

                        <form action="{{ route('topics.forceDestroy', $topic->id) }}" method="POST"
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
            {{ $topics->links() }}
        </div>
    @endif
@endsection
