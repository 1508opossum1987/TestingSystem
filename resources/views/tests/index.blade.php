@extends('layouts.app')

@section('content')
    <h1>Список тестов</h1>

    @if(session('success'))
        <div style="color: green; margin-bottom: 10px;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red; margin-bottom: 10px;">{{ session('error') }}</div>
    @endif

    <a href="{{ route('tests.create') }}"><button>Создать тест</button></a>
    <a href="{{ route('tests.trashed') }}" style="margin-left: 10px;"><button>Корзина</button></a>
    <div style="margin: 15px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; background-color: #f8f9fa;">
        <form action="{{ route('tests.index') }}" method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
            <div>
                <label for="topic_id">Тема:</label>
                <select name="topic_id" id="topic_id" style="padding: 5px;">
                    <option value="">Все темы</option>
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}" {{ request('topic_id') == $topic->id ? 'selected' : '' }}>
                            {{ $topic->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="level_id">Уровень:</label>
                <select name="level_id" id="level_id" style="padding: 5px;">
                    <option value="">Все уровни</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                            {{ $level->question_level }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" style="padding: 5px 15px;">Применить</button>
                <a href="{{ route('tests.index') }}" style="padding: 5px 15px;">Сбросить</a>
            </div>
        </form>
    </div>
    <table border="1" cellpadding="8" style="margin-top: 15px;">
        <thead>
        <tr>
            <th>ID</th>
            <th>Тема</th>
            <th>Уровень</th>
            <th>Кол-во вопросов</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tests as $test)
            <tr>
                <td>{{ $test->id }}</td>
                <td>{{ $test->topic->name ?? '—' }}</td>
                <td>{{ $test->question_level->question_level ?? '—' }}</td>
                <td>{{ $test->question_count }}</td>
                <td>
                    <a href="{{ route('tests.show', $test) }}"><button>Просмотр</button></a>
                    <a href="{{ route('tests.edit', $test) }}"><button>Редактировать</button></a>
                    @auth
                        <a href="{{ route('tests.start', $test) }}"><button style="background-color: green; color: white;">Начать тест</button></a>
                    @else
                        <button disabled style="background-color: gray;" title="Для прохождения теста необходимо авторизоваться">Начать тест</button>
                    @endauth

                    <form action="{{ route('tests.destroy', $test) }}" method="POST" style="display:inline;">
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
        {{ $tests->links() }}
    </div>
@endsection
