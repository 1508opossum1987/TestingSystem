<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тестовая система</title>
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

<hr>

<div class="container">
    @yield('content')
</div>
</body>
</html>
