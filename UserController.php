<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // パスワードハッシュ化のために追加

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', ['users' => $users]);
    }

    public function store(Request $request)
    {
        // 1. バリデーションを実施（ルールに違反したら自動で元の画面に戻る）
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8', // 最低8文字以上
        ]);

        // 2. パスワードを安全に暗号化（ハッシュ化）
        $validated['password'] = Hash::make($validated['password']);

        // 3. 安全にデータベースへ保存
        // (注意: Userモデル側で protected $fillable = ['name', 'email', 'password']; の設定が必要です)
        User::create($validated);

        return redirect('/users')->with('success', 'ユーザーを登録しました。');
    }
}