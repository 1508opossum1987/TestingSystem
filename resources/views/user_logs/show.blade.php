@extends('layouts.app')

@section('content')
    <h1>Просмотр лога</h1>

    <div>
        <strong>ID:</strong> {{ $user_log->id }}
    </div>
    <div>
        <strong>Пользователь:</strong> {{ $user_log->user->name ?? 'Не указан' }}
    </div>
    <div>
        <strong>Результат ID:</strong> {{ $user_log->result_id ?? '—' }}
    </div>
    <div>
        <strong>Путь к файлу:</strong> {{ $user_log->file_path ?? '—' }}
    </div>
    <div>
        <strong>Содержание:</strong>
        <pre>{{ $user_log->content_preview }}</pre>
    </div>
    <div>
        <strong>Дата создания:</strong> {{ $user_log->created_at }}
    </div>

    <div style="margin-top: 15px;">
        <a href="{{ route('user_logs.index') }}">Назад к списку</a>
    </div>
@endsection
