@extends('layouts.app')

@section('content')
    <h1>Просмотр темы</h1>

    <div>
        <strong>ID:</strong> {{ $topic->id }}
    </div>
    <div>
        <strong>Название:</strong> {{ $topic->name }}
    </div>
    <div>
        <strong>Slug:</strong> {{ $topic->slug }}
    </div>
    <div>
        <strong>Дата создания:</strong> {{ $topic->created_at }}
    </div>
    <div>
        <strong>Дата обновления:</strong> {{ $topic->updated_at }}
    </div>

    <div>
        <a href="{{ route('topics.edit', $topic) }}">Редактировать</a> |
        <a href="{{ route('topics.index') }}">Назад к списку</a>
    </div>
@endsection
