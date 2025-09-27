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
        'event_registration_start',
        'event_registration_end',
        'theme',
        'homepage_title',
        'homepage_subtitle',
        'homepage_description',
        'homepage_features',
        'homepage_cta_text',
        'homepage_cta_link',
    ];

    protected $casts = [
        'event_registration_start' => 'datetime',
        'event_registration_end' => 'datetime',
        'homepage_features' => 'array',
    ];

    public static function getSettings()
    {
        return self::first() ?? self::create([
            'project_name' => 'GESTEC',
            'description' => 'Sistema de Gerenciamento de Eventos Técnicos de Gestão e Desenvolvimento de Sistemas',
            'theme' => 'light',
            'homepage_title' => 'Bem-vindo ao GESTEC',
            'homepage_subtitle' => 'Sistema de Gerenciamento de Eventos Técnicos',
            'homepage_description' => 'Gerencie seus eventos, atividades e participantes de forma eficiente.',
            'homepage_features' => [
                'Gestão completa de eventos',
                'Sistema de inscrições automatizado',
                'Geração de certificados',
                'Relatórios detalhados'
            ],
            'homepage_cta_text' => 'Começar Agora',
            'homepage_cta_link' => '/dashboard',
        ]);
    }
}
