@extends('layouts.app')

@section('content')
    <h1>Список логов пользователей</h1>

    <table border="1" cellpadding="8">
        <thead>
        <tr>
            <th>ID</th>
            <th>Пользователь</th>
            <th>Результат (ID)</th>
            <th>Превью содержимого</th>
            <th>Дата</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($userLogs as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td>{{ $log->user->name }}</td>
                <td>{{ $log->result_id }}</td>
                <td>{{ $log->content_preview }}</td>
                <td>{{ $log->created_at }}</td>
                <td>
                    <button>Просмотр лога</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
