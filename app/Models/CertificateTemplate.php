<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateTemplate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'template_image_path',
        'type',
        'activity_id',
        'is_default',
        'layout_config',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'layout_config' => 'array',
    ];

    /**
     * Relacionamento com atividade (opcional)
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Obter template padrão global
     */
    public static function getDefaultTemplate()
    {
        return self::where('type', 'global')
            ->where('is_default', true)
            ->first();
    }

    /**
     * Obter template para uma atividade específica
     */
    public static function getTemplateForActivity(?Activity $activity = null)
    {
        if ($activity) {
            // Primeiro tenta template específico da atividade
            $template = self::where('type', 'activity_specific')
                ->where('activity_id', $activity->id)
                ->first();

            if ($template) {
                return $template;
            }
        }

        // Se não encontrou específico, retorna o padrão global
        return self::getDefaultTemplate();
    }

    /**
     * Scope para templates globais
     */
    public function scopeGlobal($query)
    {
        return $query->where('type', 'global');
    }

    /**
     * Scope para templates específicos de atividade
     */
    public function scopeActivitySpecific($query)
    {
        return $query->where('type', 'activity_specific');
    }
}
