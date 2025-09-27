<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalSetting extends Model
{
    protected $fillable = [
        'project_name',
        'logo_path',
        'description',
        'certificate_director_signature',
        'certificate_coordinator_signature',
        'certificate_text_template',
        'validation_base_url',
        'maintenance_mode',
        'maintenance_message',
    ];

    public static function getSettings()
    {
        return self::first() ?? self::create([
            'project_name' => 'GESTEC',
            'description' => 'Sistema de Gerenciamento de Eventos Técnicos de Gestão e Desenvolvimento de Sistemas',
        ]);
    }

    /**
     * Verificar se o sistema está em modo de manutenção
     */
    public function isMaintenanceMode(): bool
    {
        return $this->maintenance_mode ?? false;
    }

    /**
     * Ativar modo de manutenção
     */
    public function enableMaintenanceMode(string $message = null): void
    {
        $this->update([
            'maintenance_mode' => true,
            'maintenance_message' => $message,
        ]);
    }

    /**
     * Desativar modo de manutenção
     */
    public function disableMaintenanceMode(): void
    {
        $this->update([
            'maintenance_mode' => false,
            'maintenance_message' => null,
        ]);
    }
}
