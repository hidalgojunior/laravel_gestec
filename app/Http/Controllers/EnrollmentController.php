<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Mostrar atividades disponíveis para inscrição
     */
    public function index()
    {
        $user = Auth::user();
        $activities = Activity::with(['instructor', 'enrollments' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();

        return view('enrollments.index', compact('activities'));
    }

    /**
     * Inscrever usuário em uma atividade
     */
    public function enroll(Request $request, Activity $activity)
    {
        $user = Auth::user();

        // Verificar se usuário já está inscrito
        if ($activity->enrollments()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Você já está inscrito nesta atividade.');
        }

        // Verificar se usuário está desqualificado
        if ($user->isDisqualified()) {
            return back()->with('error', 'Você está desqualificado e não pode se inscrever em atividades.');
        }

        DB::transaction(function () use ($activity, $user) {
            $status = 'confirmed';

            // Verificar se há vagas disponíveis
            if (!$activity->hasAvailableSpots()) {
                if ($activity->canUseWaitingList()) {
                    $status = 'waiting_list';
                } else {
                    throw new \Exception('Não há vagas disponíveis para esta atividade.');
                }
            }

            // Criar inscrição
            Enrollment::create([
                'user_id' => $user->id,
                'activity_id' => $activity->id,
                'status' => $status,
                'enrolled_at' => now(),
            ]);
        });

        $message = $request->status === 'waiting_list'
            ? 'Você foi adicionado à fila de espera desta atividade.'
            : 'Inscrição realizada com sucesso!';

        return back()->with('success', $message);
    }

    /**
     * Cancelar inscrição
     */
    public function cancel(Enrollment $enrollment)
    {
        $user = Auth::user();

        // Verificar se a inscrição pertence ao usuário
        if ($enrollment->user_id !== $user->id) {
            return back()->with('error', 'Você não tem permissão para cancelar esta inscrição.');
        }

        // Cancelar inscrição e liberar vaga
        $enrollment->cancelAndFreeSpot();

        return back()->with('success', 'Inscrição cancelada com sucesso.');
    }

    /**
     * Upload de comprovante de pagamento
     */
    public function uploadProof(Request $request, Enrollment $enrollment)
    {
        $user = Auth::user();

        // Verificar se a inscrição pertence ao usuário
        if ($enrollment->user_id !== $user->id) {
            return back()->with('error', 'Você não tem permissão para fazer upload nesta inscrição.');
        }

        $request->validate([
            'proof_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $proofPath = $request->file('proof_file')->store('payment-proofs', 'public');

        $enrollment->update([
            'proof_file_path' => $proofPath,
            'payment_confirmed' => false, // Aguardar confirmação do organizador
        ]);

        return back()->with('success', 'Comprovante enviado com sucesso. Aguarde confirmação.');
    }

    /**
     * Confirmar pagamento (apenas organizadores/administradores)
     */
    public function confirmPayment(Enrollment $enrollment)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isOrganizador()) {
            return back()->with('error', 'Você não tem permissão para confirmar pagamentos.');
        }

        $enrollment->update(['payment_confirmed' => true]);

        return back()->with('success', 'Pagamento confirmado com sucesso.');
    }

    /**
     * Mostrar minhas inscrições
     */
    public function myEnrollments()
    {
        $user = Auth::user();
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with(['activity.instructor', 'presence'])
            ->get();

        return view('enrollments.my-enrollments', compact('enrollments'));
    }

    /**
     * Aplicar regra crítica de desqualificação
     */
    public function applyDisqualificationRule()
    {
        // Buscar usuários que não têm presença validada em nenhuma atividade
        $usersToDisqualify = User::whereHas('enrollments', function($query) {
            $query->whereDoesntHave('presence');
        })->where('is_disqualified', false)->get();

        foreach ($usersToDisqualify as $user) {
            $user->disqualifyForAbsence('Falta em atividade obrigatória - Regra Crítica de Desqualificação');
        }

        return back()->with('success', count($usersToDisqualify) . ' usuários foram desqualificados por ausência.');
    }
}
