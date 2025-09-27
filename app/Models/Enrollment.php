<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id',
        'activity_id',
        'status',
        'proof_file_path',
        'payment_confirmed',
        'enrolled_at',
    ];

    protected $casts = [
        'payment_confirmed' => 'boolean',
        'enrolled_at' => 'datetime',
    ];

    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com atividade
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Relacionamento com presença
     */
    public function presence(): HasOne
    {
        return $this->hasOne(Presence::class);
    }

    /**
     * Verificar se a presença foi validada
     */
    public function hasPresenceValidated(): bool
    {
        return $this->presence()->exists();
    }

    /**
     * Cancelar inscrição e liberar vaga para fila de espera
     */
    public function cancelAndFreeSpot()
    {
        $this->update(['status' => 'cancelled']);

        // Se há alguém na fila de espera, promover para confirmado
        $nextInWaitingList = $this->activity->getNextInWaitingList();
        if ($nextInWaitingList) {
            $nextInWaitingList->update(['status' => 'confirmed']);
            // Aqui seria implementada a notificação WhatsApp
            $this->notifyWaitingListPromotion($nextInWaitingList);
        }
    }

    /**
     * Notificar promoção da fila de espera (preparar para WhatsApp)
     */
    private function notifyWaitingListPromotion(Enrollment $enrollment)
    {
        // TODO: Implementar integração com WhatsApp via n8n
        // Por enquanto, apenas log
        \Illuminate\Support\Facades\Log::info("Usuário {$enrollment->user->name} promovido da fila de espera para atividade {$enrollment->activity->name}");
    }
}
