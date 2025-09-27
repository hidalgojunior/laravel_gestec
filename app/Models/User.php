<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'full_name',
        'cpf',
        'email',
        'whatsapp',
        'additional_contacts',
        'person_type',
        'signature_image',
        'role',
        'password',
        'must_change_password',
        'is_protected',
        'pin',
        'is_disqualified',
        'disqualification_reason',
        'disqualified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
            'is_protected' => 'boolean',
            'additional_contacts' => 'array',
            'is_disqualified' => 'boolean',
            'disqualified_at' => 'datetime',
        ];
    }

    /**
     * Accessor to ensure email is always a string
     */
    public function getEmailAttribute($value)
    {
        return is_array($value) ? (isset($value[0]) ? $value[0] : '') : $value;
    }

    /**
     * Accessor to ensure name is always a string
     */
    public function getNameAttribute($value)
    {
        return is_array($value) ? (isset($value[0]) ? $value[0] : '') : $value;
    }

    /**
     * Verificar se o usuário tem um papel específico
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Verificar se o usuário é administrador
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('administrador');
    }

    /**
     * Verificar se o usuário é organizador
     */
    public function isOrganizador(): bool
    {
        return $this->hasRole('organizador');
    }

    /**
     * Verificar se o usuário é ministrador
     */
    public function isMinistrador(): bool
    {
        return $this->hasRole('ministrador');
    }

    /**
     * Verificar se o usuário é palestrante
     */
    public function isPalestrante(): bool
    {
        return $this->hasRole('palestrante');
    }

    /**
     * Verificar se o usuário é colaborador
     */
    public function isColaborador(): bool
    {
        return $this->hasRole('colaborador');
    }

    /**
     * Verificar se o usuário é participante
     */
    public function isParticipante(): bool
    {
        return $this->hasRole('participante');
    }

    /**
     * Verificar se o usuário é parceiro
     */
    public function isParceiro(): bool
    {
        return $this->hasRole('parceiro');
    }

    /**
     * Relacionamento com atividades ministradas
     */
    public function taughtActivities()
    {
        return $this->hasMany(Activity::class, 'instructor_id');
    }

    /**
     * Relacionamento com inscrições
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Relacionamento com presenças validadas
     */
    public function validatedPresences()
    {
        return $this->hasMany(Presence::class, 'validated_by');
    }

    /**
     * Verificar se o usuário está desqualificado
     */
    public function isDisqualified(): bool
    {
        return $this->is_disqualified;
    }

    /**
     * Desqualificar usuário por falta
     */
    public function disqualifyForAbsence(string $reason = null)
    {
        $this->update([
            'is_disqualified' => true,
            'disqualification_reason' => $reason ?? 'Falta em atividade obrigatória',
            'disqualified_at' => now(),
        ]);
    }

    /**
     * Verificar se pode validar presença em uma atividade (colaborador)
     */
    public function canValidatePresenceFor(Activity $activity): bool
    {
        if (!$this->isColaborador()) {
            return false;
        }

        // Verificar se já está validando presença em outra atividade no mesmo horário
        $conflictingActivities = $this->validatedPresences()
            ->whereHas('enrollment.activity', function ($query) use ($activity) {
                $query->where('start_time', '<', $activity->end_time)
                      ->where('end_time', '>', $activity->start_time);
            })
            ->exists();

        return !$conflictingActivities;
    }

    /**
     * Gerar PIN único de 4 dígitos
     */
    public function generateUniquePin()
    {
        do {
            $pin = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('pin', $pin)->exists());

        $this->update(['pin' => $pin]);
        return $pin;
    }

    public function delete()
    {
        if ($this->is_protected) {
            throw new \Exception('Este usuário não pode ser excluído.');
        }

        return parent::delete();
    }
}
