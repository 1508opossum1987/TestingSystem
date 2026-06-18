@extends('layouts.app')

@section('content')
    <h1>Список результатов</h1>

    @if(session('success'))
        <div style="color: green; margin-bottom: 10px;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red; margin-bottom: 10px;">{{ session('error') }}</div>
    @endif

    <a href="{{ route('results.trashed') }}" style="margin-left: 10px;"><button>Корзина</button></a>
    <div style="margin: 15px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; background-color: #f8f9fa;">
        <form action="{{ route('results.index') }}" method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
            <div>
                <label for="user_search">Пользователь:</label>
                <input type="text" name="user_search" id="user_search" value="{{ request('user_search') }}" placeholder="Имя или Email..." style="padding: 5px; width: 200px;">
            </div>
            <div>
                <label for="test_id">Тест:</label>
                <select name="test_id" id="test_id" style="padding: 5px;">
                    <option value="">Все тесты</option>
                    @foreach($tests as $test)
                        <option value="{{ $test->id }}" {{ request('test_id') == $test->id ? 'selected' : '' }}>
                            {{ $test->topic->name ?? 'Тест ' . $test->id }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="start_date">С:</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" style="padding: 5px;">
            </div>
            <div>
                <label for="end_date">По:</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" style="padding: 5px;">
            </div>
            <div>
                <button type="submit" style="padding: 5px 15px;">Применить</button>
                <a href="{{ route('results.index') }}" style="padding: 5px 15px;">Сбросить</a>
            </div>
        </form>
    </div>
    <table border="1" cellpadding="8" style="margin-top: 15px;">
        <thead>
        <tr>
            <th>ID</th>
            <th>Пользователь</th>
            <th>Тест</th>
            <th>Результат (%)</th>
            <th>Оценка</th>
            <th>Дата</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($results as $result)
            <tr>
                <td>{{ $result->id }}</td>
                <td>{{ $result->user->name ?? '—' }}</td>
                <td>{{ $result->test->topic->name ?? '—' }} ({{ $result->test->question_level->question_level ?? '—' }} кл.)</td>
                <td>{{ $result->score_percent }}%</td>
                <td>{{ $result->grade ?? '—' }}</td>
                <td>{{ $result->created_at }}</td>
                <td>
                    <a href="{{ route('results.show', $result) }}"><button>Просмотр</button></a>

                    <form action="{{ route('results.destroy', $result) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Вы уверены?')">Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div style="margin-top: 15px;">
        {{ $results->links() }}
    </div>
    <div style="margin: 15px 0; display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="{{ route('results.export.csv', request()->all()) }}">
            <button style="background-color: #17a2b8; color: white; border: none; padding: 5px 15px; border-radius: 4px; cursor: pointer;">
                Экспорт CSV
            </button>
        </a>
        <a href="{{ route('results.export.excel', request()->all()) }}">
            <button style="background-color: #28a745; color: white; border: none; padding: 5px 15px; border-radius: 4px; cursor: pointer;">
                Экспорт Excel
            </button>
        </a>
    </div>
@endsection
