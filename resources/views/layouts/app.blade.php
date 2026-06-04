<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тестовая система</title>
    @auth
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.users') }}">Админка</a>
        @endif
        @if(Auth::user()->role === 'user')
            <a href="{{ route('results.my') }}">Мои результаты</a>
        @endif
    @endauth
</head>
<body>
<nav>
    <a href="{{ route('home') }}">Главная</a> |
    <a href="{{ route('topics.index') }}">Темы</a> |
    <a href="{{ route('questions.index') }}">Вопросы</a> |
    <a href="{{ route('question_levels.index') }}">Уровни</a> |
    <a href="{{ route('tests.index') }}">Тесты</a> |
    <a href="{{ route('results.index') }}">Результаты</a> |
    <a href="{{ route('user_logs.index') }}">Логи</a>
</nav>

@auth
    <div style="margin-left: auto;">
        Привет, {{ Auth::user()->name }} ({{ Auth::user()->role }})
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit">Выйти</button>
        </form>
    </div>
@else
    <a href="{{ route('login') }}">Войти</a>
    <a href="{{ route('register') }}">Регистрация</a>
@endauth

<hr>

<div class="container">
    @yield('content')
</div>
</body>
</html>
