<?php

namespace App\Http\Controllers;

use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserLogController extends Controller
{
    //Возможно не пригодится
    public function index(): View
    {
        $userLogs=UserLog::query()
            ->orderBy('created_at')
            ->with(['user', 'result'])
            ->get();

        return view('user_logs.index', [
            'user_logs'=>$userLogs
        ]);
    }

    public function show(UserLog $user_log): View
    {
        return view('user_log.show', ['user_log'=>$user_log]);
    }
}
