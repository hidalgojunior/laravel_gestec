<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Enrollment;
use App\Models\Presence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresenceController extends Controller
{
    /**
     * Mostrar interface de validação de presença
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->isColaborador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $activities = Activity::with('enrollments.user')->get();

        return view('presences.index', compact('activities'));
    }

    /**
     * Validar presença usando PIN
     */
    public function validatePresence(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|size:4',
            'activity_id' => 'required|exists:activities,id',
        ]);

        $user = Auth::user();

        if (!$user->isColaborador()) {
            return response()->json(['error' => 'Acesso negado.'], 403);
        }

        // Buscar usuário pelo PIN
        $participant = User::where('pin', $request->pin)->first();

        if (!$participant) {
            return response()->json(['error' => 'PIN inválido.'], 404);
        }

        // Verificar se o participante está inscrito na atividade
        $enrollment = Enrollment::where('user_id', $participant->id)
            ->where('activity_id', $request->activity_id)
            ->where('status', 'confirmed')
            ->first();

        if (!$enrollment) {
            return response()->json(['error' => 'Participante não está inscrito nesta atividade.'], 404);
        }

        // Verificar se presença já foi validada
        if ($enrollment->hasPresenceValidated()) {
            return response()->json(['error' => 'Presença já foi validada para este participante.'], 400);
        }

        // Verificar se colaborador pode validar presença nesta atividade (sem conflito de horário)
        $activity = Activity::find($request->activity_id);
        if (!$user->canValidatePresenceFor($activity)) {
            return response()->json(['error' => 'Você não pode validar presença nesta atividade devido a conflito de horário.'], 403);
        }

        // Criar registro de presença
        Presence::create([
            'enrollment_id' => $enrollment->id,
            'validated_by' => $user->id,
            'validated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Presença validada com sucesso!',
            'participant' => $participant->name,
        ]);
    }

    /**
     * Mostrar relatório de presenças por atividade
     */
    public function report(Activity $activity)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isOrganizador() && !$user->isColaborador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $enrollments = $activity->enrollments()
            ->with(['user', 'presence.validator'])
            ->get();

        return view('presences.report', compact('activity', 'enrollments'));
    }

    /**
     * Remover validação de presença (apenas administradores)
     */
    public function removePresence(Presence $presence)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isOrganizador()) {
            return back()->with('error', 'Acesso negado.');
        }

        $presence->delete();

        return back()->with('success', 'Validação de presença removida com sucesso.');
    }
}
