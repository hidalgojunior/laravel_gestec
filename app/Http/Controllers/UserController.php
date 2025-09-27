<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Exibir lista de usuários por papel
     */
    public function index(Request $request)
    {
        $role = $request->get('role', 'todos');
        $users = $role === 'todos'
            ? User::all()
            : User::where('role', $role)->get();

        return view('admin.users.index', compact('users', 'role'));
    }

    /**
     * Mostrar formulário de criação de usuário
     */
    public function create(Request $request)
    {
        $role = $request->get('role', 'participante');
        return view('admin.users.create', compact('role'));
    }

    /**
     * Armazenar novo usuário
     */
    public function store(Request $request)
    {
        $role = $request->input('role', 'participante');

        $validator = $this->getValidatorForRole($request, $role);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->input('name'),
            'full_name' => $request->input('full_name'),
            'cpf' => $request->input('cpf'),
            'email' => $request->input('email'),
            'whatsapp' => $request->input('whatsapp'),
            'additional_contacts' => $request->input('additional_contacts'),
            'person_type' => $request->input('person_type'),
            'role' => $role,
            'password' => Hash::make($request->input('password')),
            'must_change_password' => true,
        ]);

        // Upload da assinatura se for organizador
        if ($role === 'organizador' && $request->hasFile('signature_image')) {
            $path = $request->file('signature_image')->store('signatures', 'public');
            $user->update(['signature_image' => $path]);
        }

        return redirect()->route('admin.users.index', ['role' => $role])
            ->with('success', ucfirst($role) . ' cadastrado com sucesso!');
    }

    /**
     * Mostrar usuário específico
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Atualizar usuário
     */
    public function update(Request $request, User $user)
    {
        $validator = $this->getValidatorForRole($request, $user->role, $user->id);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateData = [
            'name' => $request->input('name'),
            'full_name' => $request->input('full_name'),
            'cpf' => $request->input('cpf'),
            'email' => $request->input('email'),
            'whatsapp' => $request->input('whatsapp'),
            'additional_contacts' => $request->input('additional_contacts'),
            'person_type' => $request->input('person_type'),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->input('password'));
            $updateData['must_change_password'] = true;
        }

        $user->update($updateData);

        // Upload da assinatura se for organizador
        if ($user->role === 'organizador' && $request->hasFile('signature_image')) {
            $path = $request->file('signature_image')->store('signatures', 'public');
            $user->update(['signature_image' => $path]);
        }

        return redirect()->route('admin.users.index', ['role' => $user->role])
            ->with('success', ucfirst($user->role) . ' atualizado com sucesso!');
    }

    /**
     * Remover usuário
     */
    public function destroy(User $user)
    {
        if ($user->is_protected) {
            return redirect()->back()->with('error', 'Este usuário não pode ser excluído.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }

    /**
     * Obter validador específico para cada papel
     */
    private function getValidatorForRole(Request $request, string $role, int $userId = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'cpf' => ['required', 'string', 'size:14', 'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', Rule::unique('users', 'cpf')->ignore($userId)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
        ];

        // Regras específicas por papel
        switch ($role) {
            case 'ministrador':
                $rules['whatsapp'] = 'required|string|max:20';
                $rules['additional_contacts'] = 'nullable|array';
                break;

            case 'organizador':
                $rules['person_type'] = 'required|in:pf,pj';
                $rules['signature_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
                break;

            case 'colaborador':
            case 'participante':
            case 'parceiro':
                // Regras básicas já cobrem
                break;
        }

        // Senha obrigatória apenas na criação
        if (!$userId) {
            $rules['password'] = 'required|string|min:8|confirmed';
        } else {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        return Validator::make($request->all(), $rules, $this->getValidationMessages());
    }

    /**
     * Mensagens de validação personalizadas
     */
    private function getValidationMessages()
    {
        return [
            'full_name.required' => 'O nome completo é obrigatório.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.size' => 'O CPF deve ter exatamente 14 caracteres.',
            'cpf.regex' => 'O CPF deve estar no formato XXX.XXX.XXX-XX.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'whatsapp.required' => 'O WhatsApp é obrigatório para ministradores.',
            'person_type.required' => 'O tipo de pessoa é obrigatório para organizadores.',
            'person_type.in' => 'O tipo de pessoa deve ser PF ou PJ.',
            'signature_image.image' => 'A assinatura deve ser uma imagem.',
            'signature_image.mimes' => 'A assinatura deve ser um arquivo do tipo: jpeg, png, jpg, gif.',
            'signature_image.max' => 'A assinatura não pode ser maior que 2MB.',
        ];
    }
}
