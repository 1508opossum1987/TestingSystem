@extends('layouts.app')

@section('content')
    <h1>Мои результаты</h1>

    <table border="1" cellpadding="8">
        <thead>
        <tr>
            <th>ID</th>
            <th>Тест</th>
            <th>Результат (%)</th>
            <th>Оценка</th>
            <th>Дата</th>
        </tr>
        </thead>
        <tbody>
        @foreach($results as $result)
            <tr>
                <td>{{ $result->id }}</td>
                <td>{{ $result->test->topic->name ?? '—' }} ({{ $result->test->question_level->question_level ?? '—' }} кл.)</td>
                <td>{{ $result->score_percent }}%</td>
                <td>{{ $result->grade ?? '—' }}</td>
                <td>{{ $result->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div style="margin-top: 15px;">
        {{ $results->links() }}
    </div>
@endsection
