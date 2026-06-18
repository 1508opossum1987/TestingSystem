<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тестовая система</title>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.3.0/dist/web/pusher.min.js"></script>
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

    @auth
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.users') }}">Админка</a>
        @endif
        @if(Auth::user()->role === 'user')
            <a href="{{ route('results.my') }}">Мои результаты</a>
        @endif
    @endauth
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
    <div>Для прохождения теста необходимо <a href="{{ route('login') }}">зарегистрироваться</a></div>
    <a href="{{ route('login') }}">Войти</a>
    <a href="{{ route('register') }}">Регистрация</a>
@endauth

<hr>

<div class="container">
    @yield('content')
</div>

@auth
    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'teacher')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                window.Echo = new Echo({
                    broadcaster: 'reverb',
                    key: '{{ env('REVERB_APP_KEY') }}',
                    wsHost: '{{ env('REVERB_HOST') }}',
                    wsPort: {{ env('REVERB_PORT') }},
                    forceTLS: false,
                    enabledTransports: ['ws', 'wss'],
                });
                window.Echo.channel('notifications')
                    .listen('.new.result', (data) => {
                        showNotification(data);
                    });

                function showNotification(data) {
                    const container = document.getElementById('notification-container');

                    const notification = document.createElement('div');
                    notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #28a745;
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 9999;
                max-width: 400px;
                font-family: Arial, sans-serif;
                animation: slideIn 0.3s ease;
                border-left: 4px solid #155724;
            `;

                    notification.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                        <strong>🔔 Новый результат!</strong>
                        <div style="margin-top: 5px; font-size: 14px;">${data.message}</div>
                        <div style="margin-top: 3px; font-size: 12px; opacity: 0.8;">
                            Пользователь: ${data.userName} | Баллы: ${data.score}%
                        </div>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; font-size: 18px; cursor: pointer;">✕</button>
                </div>
                <div style="margin-top: 10px;">
                    <a href="/results/${data.resultId}" style="color: white; text-decoration: underline; font-size: 13px;">Перейти к результату</a>
                </div>
            `;

                    container.appendChild(notification);


                    setTimeout(() => {
                        if (notification.parentElement) {
                            notification.style.opacity = '0';
                            notification.style.transition = 'opacity 0.3s';
                            setTimeout(() => notification.remove(), 300);
                        }
                    }, 8000);
                }

                const container = document.createElement('div');
                container.id = 'notification-container';
                document.body.appendChild(container);

                const style = document.createElement('style');
                style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            #notification-container {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                display: flex;
                flex-direction: column;
                gap: 10px;
                pointer-events: none;
            }
            #notification-container > * {
                pointer-events: auto;
            }
        `;
                document.head.appendChild(style);
            });
        </script>
    @endif
@endauth

</body>
</html>
