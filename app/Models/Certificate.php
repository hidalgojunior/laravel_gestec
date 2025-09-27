<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
        'activity_id',
        'certificate_type',
        'total_hours',
        'validation_code',
        'pdf_path',
        'certificate_data',
        'issued_at',
    ];

    protected $casts = [
        'certificate_data' => 'array',
        'issued_at' => 'datetime',
        'total_hours' => 'decimal:2',
    ];

    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com atividade (opcional)
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Gerar código de validação único
     */
    public static function generateValidationCode(): string
    {
        do {
            $code = strtoupper(Str::random(32));
        } while (self::where('validation_code', $code)->exists());

        return $code;
    }

    /**
     * Calcular carga horária baseada no tipo de certificado
     */
    public static function calculateHours(User $user, ?Activity $activity = null): float
    {
        return match($user->role) {
            'participante', 'colaborador' => self::calculateParticipantHours($user),
            'ministrador' => self::calculateInstructorHours($user),
            'organizador' => self::calculateOrganizerHours(),
            'parceiro' => 0, // Certificado de agradecimento
            default => 0,
        };
    }

    /**
     * Calcular horas para participante/colaborador
     * Soma das horas das atividades com presença, arredondamento para cima
     */
    private static function calculateParticipantHours(User $user): float
    {
        $totalHours = 0;

        foreach ($user->enrollments()->where('status', 'confirmed')->get() as $enrollment) {
            if ($enrollment->hasPresenceValidated()) {
                // Arredondamento para cima: 1h30 → 2h
                $hours = ceil($enrollment->activity->workload_hours);
                $totalHours += $hours;
            }
        }

        return $totalHours;
    }

    /**
     * Calcular horas para ministrador
     * Carga horária do participante na atividade × 2,5
     */
    private static function calculateInstructorHours(User $user): float
    {
        $totalHours = 0;

        foreach ($user->taughtActivities as $activity) {
            // Para cada atividade ministrada, calcula as horas dos participantes
            $participantHours = $activity->enrollments()
                ->where('status', 'confirmed')
                ->whereHas('presence')
                ->count() * $activity->workload_hours;

            // Multiplica por 2,5
            $totalHours += $participantHours * 2.5;
        }

        return ceil($totalHours);
    }

    /**
     * Calcular horas para organizador
     * Carga horária total do evento × 3
     */
    private static function calculateOrganizerHours(): float
    {
        $totalEventHours = Activity::sum('workload_hours');
        return $totalEventHours * 3;
    }

    /**
     * Verificar se certificado é válido
     */
    public function isValid(): bool
    {
        // Certificado é válido se não foi revogado e usuário não foi desqualificado
        return !$this->user->is_disqualified;
    }

    /**
     * Obter URL de validação pública
     */
    public function getValidationUrl(): string
    {
        $settings = GlobalSetting::getSettings();
        return $settings->validation_base_url . '/' . $this->validation_code;
    }

    /**
     * Gerar dados do certificado para template
     */
    public function generateCertificateData(): array
    {
        $settings = GlobalSetting::getSettings();

        return [
            'participant_name' => $this->user->full_name ?? $this->user->name,
            'participant_cpf' => $this->user->cpf,
            'certificate_type' => $this->getCertificateTypeLabel(),
            'activity_name' => $this->activity?->name,
            'total_hours' => $this->total_hours,
            'issued_date' => $this->issued_at->format('d/m/Y'),
            'validation_code' => $this->validation_code,
            'validation_url' => $this->getValidationUrl(),
            'director_signature' => $settings->certificate_director_signature,
            'coordinator_signature' => $settings->certificate_coordinator_signature,
        ];
    }

    /**
     * Obter label do tipo de certificado
     */
    private function getCertificateTypeLabel(): string
    {
        return match($this->certificate_type) {
            'participant' => 'Participante',
            'collaborator' => 'Colaborador',
            'instructor' => 'Ministrador',
            'organizer' => 'Organizador',
            'partner' => 'Parceiro',
            default => 'Participante',
        };
    }
}
