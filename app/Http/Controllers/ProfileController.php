<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('user.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('user.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validação estrita: Nome e Email obrigatórios. Os novos são 100% opcionais.
        $request->validate([
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'birthday' => 'nullable|date|before:today',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only(['name', 'email', 'birthday', 'phone']);

        // Se introduziu uma nova password, atualiza-a
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Se fez upload de uma nova foto de perfil
        if ($request->hasFile('photo')) {
            // Apaga a foto antiga se ela existir para não acumular lixo no disco
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            // Guarda a nova foto na pasta 'profiles' dentro de storage/app/public
            $data['photo'] = $request->file('photo')->store('profiles', 'public');
        }

        $user->update($data);

        return redirect()->route('profile.show')->with('success', 'Perfil atualizado com sucesso!');
    }
}
