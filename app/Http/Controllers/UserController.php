<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|in:admin,employee']);

        if ($user->id === auth()->id() && $request->role !== 'admin') {
            return back()->with('error', 'Não podes alterar o teu próprio papel para algo que não seja admin.');
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', 'Role do utilizador atualizado com sucesso!');

    }
    public function index()
{
    $users = User::all();
    return view('users.index', compact('users'));
}
}
