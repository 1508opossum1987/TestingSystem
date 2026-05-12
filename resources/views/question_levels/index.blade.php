@extends('layouts.app')

@section('content')
    <h1>Список уровней (классов)</h1>

    <table border="1" cellpadding="8">
        <thead>
        <tr>
            <th>ID</th>
            <th>Уровень</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($question_levels as $level)
            <tr>
                <td>{{ $level->id }}</td>
                <td>{{ $level->level }} класс</td>
                <td>
                    <button>Просмотр</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
