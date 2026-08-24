<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * 管理者画面（一覧・登録画面）を表示する
     */
    public function index(): View
    {
        // 登録されているユーザー一覧を取得
        $users = User::latest()->get();

        // ビューに $users を渡す
        return view('admin.dashboard', compact('users')); // ※実際のBladeファイル名（admin.users など）に合算してください
    }

    /**
     * 管理者が学生アカウントを作成する。
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => User::USER_ROLE_ID,
            'email_verified_at' => now(),
        ]);

        return back()->with('accountCreated', [
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);
    }
}