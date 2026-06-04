@extends('layouts.app')

@section('content')
    <h1>Управление пользователями</h1>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <table border="1" cellpadding="8">
        <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Email</th>
            <th>Текущая роль</th>
            <th>Изменить роль</th>
            <th>Активен</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>
                    <form action="{{ route('admin.users.updateRole', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select name="role">
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="teacher" {{ $user->role == 'teacher' ? 'selected' : '' }}>Teacher</option>
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                        </select>
                        <button type="submit">Сохранить</button>
                    </form>
                </td>
                <td>
                    @if($user->is_active)
                        <span style="color: green;">Да</span>
                    @else
                        <span style="color: red;">Нет</span>
                    @endif
                </td>
                <td>
                    <form action="{{ route('admin.users.toggleActive', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit">
                            {{ $user->is_active ? 'Заблокировать' : 'Разблокировать' }}
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
@endsection
