<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    const PAGINATE_PER_PAGE=12;
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'is_active' => true,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Неверные учетные данные.'],
            ]);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Ваш аккаунт заблокирован.',
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Выход выполнен успешно.',
        ]);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    }

    public function users(): JsonResponse
    {
        $users = User::paginate(self::PAGINATE_PER_PAGE);

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    public function updateRole(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'role' => 'required|in:admin,teacher,user',
        ]);

        $user->role = $request->role;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => "Роль пользователя '{$user->name}' изменена на '{$user->role}'",
            'data' => $user,
        ]);
    }

    public function toggleActive(User $user): JsonResponse
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'разблокирован' : 'заблокирован';

        return response()->json([
            'success' => true,
            'message' => "Пользователь '{$user->name}' успешно {$status}",
            'data' => $user,
        ]);
    }
}
