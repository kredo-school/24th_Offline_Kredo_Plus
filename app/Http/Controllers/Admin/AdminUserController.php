<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * 管理者画面（一覧・登録画面）を表示する
     */
    public function index(): View
    {
        $users = User::latest()->get();

        return view('admin.dashboard', compact('users'));
    }

    /**
     * 管理者が新規アカウントを作成する。
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role'     => ['required', 'string', 'in:student,admin,user'],
        ]);

        // role に応じて role_id を設定
        $roleId = ($validated['role'] === 'admin') 
            ? (defined('App\Models\User::ADMIN_ROLE_ID') ? User::ADMIN_ROLE_ID : 1)
            : (defined('App\Models\User::USER_ROLE_ID') ? User::USER_ROLE_ID : 2);

        User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'role_id'           => $roleId,
            'email_verified_at' => now(),
        ]);

        return back()->with('accountCreated', [
            'email'    => $validated['email'],
            'password' => $validated['password'],
        ]);
    }

    /**
     * ユーザーのプロパティ（性別・ステータスなど）を一括更新する。
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'gender' => ['nullable', 'string', 'in:male,female'],
            'dorm'   => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
        ]);

        $updateData = [];

        if (array_key_exists('gender', $validated)) {
            $updateData['gender'] = $validated['gender'];
        }

        if (array_key_exists('dorm', $validated)) {
            $updateData['dorm'] = $validated['dorm'];
        }

        // ステータス（active / inactive）の更新処理
        if (!empty($validated['status'])) {
            if (Schema::hasColumn('users', 'status')) {
                $updateData['status'] = $validated['status'];
            } elseif (Schema::hasColumn('users', 'is_active')) {
                $updateData['is_active'] = ($validated['status'] === 'active');
            }
        }

        if (!empty($updateData)) {
            $user->update($updateData);
        }

        return back()->with('success', 'ユーザー情報を更新しました。');
    }

    /**
     * アカウントの利用停止 / 再開を切り替える（即時個別切り替え用）。
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        if (Schema::hasColumn('users', 'status')) {
            $isCurrentlyActive = ($user->status !== 'inactive');
            $newStatus = $isCurrentlyActive ? 'inactive' : 'active';
            
            $user->update([
                'status' => $newStatus,
            ]);

            $statusMessage = ($newStatus === 'active') ? 'アカウントを再開しました。' : 'アカウントを停止しました。';
        } elseif (Schema::hasColumn('users', 'is_active')) {
            $user->update([
                'is_active' => ! $user->is_active,
            ]);

            $statusMessage = $user->is_active ? 'アカウントを再開しました。' : 'アカウントを停止しました。';
        } else {
            return back()->with('error', 'データベースにステータス管理用カラムが見つかりません。');
        }

        return back()->with('success', $statusMessage);
    }

    /**
     * アカウントを削除する。
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return back()->with('success', 'ユーザーを削除しました。');
    }
}