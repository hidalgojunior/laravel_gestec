<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventEnrollment;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventEnrollmentController extends Controller
{
    /**
     * Inscrever usuário em um evento e automaticamente em todas as atividades
     */
    public function enroll(Request $request, Event $event)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userId = $request->user_id;

        DB::transaction(function () use ($event, $userId) {
            // Criar inscrição no evento
            $eventEnrollment = EventEnrollment::create([
                'user_id' => $userId,
                'event_id' => $event->id,
                'enrolled_at' => now(),
            ]);

            // Inscrever automaticamente em todas as atividades do evento
            foreach ($event->activities as $activity) {
                // Verificar se já não está inscrito nesta atividade
                $existingEnrollment = Enrollment::where('user_id', $userId)
                    ->where('activity_id', $activity->id)
                    ->first();

                if (!$existingEnrollment) {
                    $status = $activity->hasAvailableSpots() ? 'confirmed' : 'waiting_list';

                    Enrollment::create([
                        'user_id' => $userId,
                        'activity_id' => $activity->id,
                        'status' => $status,
                        'enrolled_at' => now(),
                    ]);
                }
            }
        });

        return redirect()->back()->with('success', 'Inscrição realizada com sucesso no evento e em todas as suas atividades.');
    }

    /**
     * Cancelar inscrição no evento e em todas as atividades
     */
    public function cancel(Event $event, $userId)
    {
        DB::transaction(function () use ($event, $userId) {
            // Cancelar inscrição no evento
            EventEnrollment::where('user_id', $userId)
                ->where('event_id', $event->id)
                ->update(['status' => 'cancelled']);

            // Cancelar inscrições em todas as atividades do evento
            foreach ($event->activities as $activity) {
                Enrollment::where('user_id', $userId)
                    ->where('activity_id', $activity->id)
                    ->update(['status' => 'cancelled']);
            }
        });

        return redirect()->back()->with('success', 'Inscrição cancelada com sucesso.');
    }

    /**
     * Listar inscrições de um evento
     */
    public function index(Event $event)
    {
        $enrollments = $event->eventEnrollments()->with('user')->get();
        return view('admin.events.enrollments.index', compact('event', 'enrollments'));
    }
}
