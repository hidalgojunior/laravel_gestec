<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventEnrollment extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'status',
        'enrolled_at',
    ];

    protected $casts = [
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
     * Relacionamento com evento
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Inscrições automáticas nas atividades do evento
     */
    public function activityEnrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'user_id', 'user_id')
                    ->whereHas('activity', function ($query) {
                        $query->where('event_id', $this->event_id);
                    });
    }
}
