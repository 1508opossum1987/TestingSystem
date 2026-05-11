@extends('layouts.app')

@section('content')
    <h1>Список тем</h1>

    <button>Создать тему</button>

    <table border="1" cellpadding="8">
        <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($topics as $topic)
            <tr>
                <td>{{ $topic->id }}</td>
                <td>{{ $topic->name }}</td>
                <td>
                    <button>Просмотр</button>
                    <button>Редактировать</button>
                    <button>Удалить</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
