<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Lista os utilizadores (Apenas o próprio se for Employee, ou todos se for Admin)
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Mostra o formulário de criação (Apenas Admin)
     */
    public function create()
    {
        $this->authorize('admin-only');

        return view('users.create');
    }

    /**
     * Grava o novo utilizador com tradução e obrigatoriedade de mudar password
     */
    public function store(Request $request)
    {
        $this->authorize('admin-only');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:admin,employee',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'Introduza um endereço de email válido.',
            'email.unique' => 'Este email já está registado no sistema.',
            'role.required' => 'Deve selecionar uma função.',
            'password.required' => 'O campo password é obrigatório.',
            'password.min' => 'A password deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'As passwords introduzidas não coincidem.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'must_change_password' => true, // Obriga a mudar no primeiro acesso
        ]);

        return redirect()->route('users.index')->with('success', 'Utilizador criado com sucesso!');
    }

    /**
     * Abre o ecrã de Edição (Trancado contra espionagem de IDs)
     */
    public function edit(User $user)
    {
        // Se não for admin e tentar aceder ao ID de outro utilizador -> Erro 403
        if (auth()->user()->role !== 'admin' && $user->id !== auth()->id()) {
            abort(403, 'Não tens permissão para editar este utilizador.');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Processa a atualização dos dados do utilizador (Absorveu o ProfileController)
     */
    public function update(Request $request, User $user)
    {
        // 1. Barreira de Segurança
        if (auth()->user()->role !== 'admin' && $user->id !== auth()->id()) {
            abort(403, 'Não tens permissão para atualizar este utilizador.');
        }

        // 2. Validações Core (Nome e Email) + Campos Opcionais
        $rules = [
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'birthday' => 'nullable|date|before:today',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        if (auth()->user()->role === 'admin' && $request->has('role')) {
            $rules['role'] = 'required|in:admin,employee';
        }

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validated = $request->validate($rules, [
            'name.required' => 'O campo "Nome" é obrigatório.',
            'email.unique' => 'Este email já se encontra em uso.',
            'birthday.before' => 'A data de nascimento tem de ser anterior ao dia de hoje.',
            'photo.image' => 'O ficheiro enviado tem de ser uma imagem válida.',
            'password.min' => 'A nova password deve conter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da password não coincide.',
        ]);

        // 3. Segurança: Impedir o Admin de se despromover a si próprio
        if (auth()->user()->role === 'admin' && isset($validated['role'])) {
            if ($user->id === auth()->id() && $validated['role'] !== 'admin') {
                return back()->with('error', 'Não pode alterar o seu próprio nível de acesso.');
            }
            $user->role = $validated['role'];
        }

        // 4. Tratamento do Upload da Foto de Perfil
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('profiles', 'public');
        }

        // 5. Atualização dos restantes dados
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->birthday = $validated['birthday'] ?? $user->birthday;
        $user->phone = $validated['phone'] ?? $user->phone;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Utilizador atualizado com sucesso!');
    }

    /**
     * Remove um utilizador do sistema (Apenas Admin)
     */
    public function destroy(User $user)
    {
        $this->authorize('admin-only');

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Não te podes eliminar a ti próprio do sistema.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Utilizador eliminado com sucesso!');
    }
    public function deletePhoto(User $user)
    {
        if (auth()->user()->role !== 'admin' && $user->id !== auth()->id()) {
            abort(403);
        }

        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);

            $user->photo = null;
            $user->save();
        }

        return back()->with('success', 'Foto removida com sucesso');
    }
    public function show(Request $request, $id = null)
    {
        if (!$id && !empty($request->query())) {
            // Pega na primeira chave da query string, que no teu caso é o ID "1"
            $id = key($request->query());
        }

        $id = $id ?? auth()->id();

        $user = User::findOrFail($id);

        if (auth()->user()->role !== 'admin' && $user->id !== auth()->id()) {
            abort(403, 'Não tens permissão para visualizar este utilizador.');
        }

        return view('users.show', compact('user'));
    }

    public function requestAdmin(User $user)
    {
        $user->update(['admin_request_status' => 'pending']);
        return redirect()->back()->with('success', 'Pedido de alteração enviado com sucesso.');
    }

    public function handleAdminRequest(User $user, $action)
{
    if ($action === 'approve') {
        $user->update([
            'role' => 'admin',
            'admin_request_status' => 'approved'
        ]);
        return redirect()->back()->with('success', "A conta de utilizador dispõe agora de privilégios de Admin.");
    }

    if ($action === 'reject') {
        $user->update(['admin_request_status' => 'rejected']);
        return redirect()->back()->with('error', "O pedido de alteração de nível de acesso foi recusado.");
    }

    return redirect()->back();
}

public function dismissNotification(User $user)
{    $user->update(['admin_request_status' => null]);
    return redirect()->back();
}
}
