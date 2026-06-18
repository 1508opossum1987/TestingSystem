@extends('layouts.app')

@section('content')
    <h1>Список логов пользователей</h1>

    <table border="1" cellpadding="8">
        <thead>
        <tr>
            <th>ID</th>
            <th>Пользователь</th>
            <th>Результат</th>
            <th>Содержание</th>
            <th>Дата</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($user_logs as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td>{{ $log->user->name ?? '—' }}</td>
                <td>{{ $log->result_id ?? '—' }}</td>
                <td>{{ Str::limit($log->content_preview, 50) }}</td>
                <td>{{ $log->created_at }}</td>
                <td>
                    <a href="{{ route('user_logs.show', $log) }}">
                        <button>Просмотр</button>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div style="margin-top: 15px;">
        {{ $user_logs->links() }}
    </div>
@endsection
