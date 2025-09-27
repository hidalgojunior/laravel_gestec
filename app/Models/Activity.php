<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    protected $fillable = [
        'name',
        'description',
        'instructor_id',
        'event_id',
        'start_time',
        'end_time',
        'workload_hours',
        'total_spots',
        'has_cost',
        'pix_key',
        'proof_file_path',
        'waiting_list_enabled',
        'type',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'has_cost' => 'boolean',
        'waiting_list_enabled' => 'boolean',
    ];

    /**
     * Relacionamento com o instrutor (ministrador)
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Relacionamento com o evento (opcional)
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Relacionamento com inscrições
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Inscrições confirmadas
     */
    public function confirmedEnrollments(): HasMany
    {
        return $this->enrollments()->where('status', 'confirmed');
    }

    /**
     * Inscrições na fila de espera
     */
    public function waitingListEnrollments(): HasMany
    {
        return $this->enrollments()->where('status', 'waiting_list');
    }

    /**
     * Verificar se há vagas disponíveis
     */
    public function hasAvailableSpots(): bool
    {
        return $this->confirmedEnrollments()->count() < $this->total_spots;
    }

    /**
     * Verificar se a fila de espera está habilitada
     */
    public function canUseWaitingList(): bool
    {
        return $this->waiting_list_enabled;
    }

    /**
     * Obter o próximo da fila de espera
     */
    public function getNextInWaitingList()
    {
        return $this->waitingListEnrollments()->orderBy('enrolled_at')->first();
    }

    /**
     * Verificar se há conflito de horário com outra atividade
     */
    public function hasTimeConflict(Activity $otherActivity): bool
    {
        return $this->start_time < $otherActivity->end_time && $this->end_time > $otherActivity->start_time;
    }
}
