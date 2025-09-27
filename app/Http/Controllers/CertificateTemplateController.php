<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\CertificateTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CertificateTemplateController extends Controller
{
    public function __construct()
    {
        // Middleware será definido nas rotas
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isOrganizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $templates = CertificateTemplate::with('activity')
            ->orderBy('is_global', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $activities = Activity::orderBy('name')->get();

        return view('certificate-templates.index', compact('templates', 'activities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isOrganizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $activities = Activity::orderBy('name')->get();

        return view('certificate-templates.create', compact('activities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isOrganizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'background_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'activity_id' => 'nullable|exists:activities,id',
            'is_global' => 'boolean',
        ]);

        // Se for global, não pode ter activity_id
        if ($request->boolean('is_global')) {
            $request->merge(['activity_id' => null]);
        }

        // Verificar se já existe template global ou para esta atividade
        $existingTemplate = CertificateTemplate::where('is_global', $request->boolean('is_global'))
            ->when($request->activity_id, fn($q) => $q->where('activity_id', $request->activity_id))
            ->first();

        if ($existingTemplate) {
            return back()->withErrors([
                'template' => $request->boolean('is_global')
                    ? 'Já existe um template global.'
                    : 'Já existe um template para esta atividade.'
            ])->withInput();
        }

        // Upload da imagem
        $imagePath = $request->file('background_image')->store('certificate-templates', 'public');

        CertificateTemplate::create([
            'name' => $request->name,
            'background_image_path' => $imagePath,
            'activity_id' => $request->activity_id,
            'is_global' => $request->boolean('is_global'),
        ]);

        return redirect()->route('certificate-templates.index')
            ->with('success', 'Template criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CertificateTemplate $certificateTemplate)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isOrganizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        return view('certificate-templates.show', compact('certificateTemplate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CertificateTemplate $certificateTemplate)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isOrganizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $activities = Activity::orderBy('name')->get();

        return view('certificate-templates.edit', compact('certificateTemplate', 'activities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CertificateTemplate $certificateTemplate)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isOrganizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'activity_id' => 'nullable|exists:activities,id',
            'is_global' => 'boolean',
        ]);

        // Se for global, não pode ter activity_id
        if ($request->boolean('is_global')) {
            $request->merge(['activity_id' => null]);
        }

        // Verificar se já existe template global ou para esta atividade (exceto o atual)
        $existingTemplate = CertificateTemplate::where('is_global', $request->boolean('is_global'))
            ->where('id', '!=', $certificateTemplate->id)
            ->when($request->activity_id, fn($q) => $q->where('activity_id', $request->activity_id))
            ->first();

        if ($existingTemplate) {
            return back()->withErrors([
                'template' => $request->boolean('is_global')
                    ? 'Já existe um template global.'
                    : 'Já existe um template para esta atividade.'
            ])->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'activity_id' => $request->activity_id,
            'is_global' => $request->boolean('is_global'),
        ];

        // Upload de nova imagem se fornecida
        if ($request->hasFile('background_image')) {
            // Remover imagem antiga
            if ($certificateTemplate->background_image_path) {
                Storage::disk('public')->delete($certificateTemplate->background_image_path);
            }

            $imagePath = $request->file('background_image')->store('certificate-templates', 'public');
            $updateData['background_image_path'] = $imagePath;
        }

        $certificateTemplate->update($updateData);

        return redirect()->route('certificate-templates.index')
            ->with('success', 'Template atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CertificateTemplate $certificateTemplate)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isOrganizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        // Remover imagem
        if ($certificateTemplate->background_image_path) {
            Storage::disk('public')->delete($certificateTemplate->background_image_path);
        }

        $certificateTemplate->delete();

        return redirect()->route('certificate-templates.index')
            ->with('success', 'Template removido com sucesso.');
    }
}
