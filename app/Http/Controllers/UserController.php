<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    private const int ITEMS_PER_PAGE = 12;

    public function index(): View
    {
        $users = User::paginate(self::ITEMS_PER_PAGE);
        return view('admin.users.index', ['users' => $users]);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'role' => 'required|in:admin,teacher,user',
        ]);

        $user->role = $request->role;
        $user->save();

        return redirect()
            ->route('admin.users')
            ->with('success', "Роль пользователя '{$user->name}' изменена на '{$user->role}'");
    }

    public function toggleActive(User $user): RedirectResponse
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'разблокирован' : 'заблокирован';

        return redirect()
            ->route('admin.users')
            ->with('success', "Пользователь '{$user->name}' успешно {$status}");
    }
}
