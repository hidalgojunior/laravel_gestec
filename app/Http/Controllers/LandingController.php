<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Event;
use App\Models\User;
use App\Models\GlobalSetting;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Buscar dados dinâmicos
        $activities = Activity::with(['instructor'])->orderBy('start_time')->get();
        $events = Event::all();
        $organizers = User::where('role', 'organizador')->get();
        $settings = GlobalSetting::getSettings();

        // Dados estáticos da Etec
        $etecInfo = [
            'name' => 'Etec Antonio Devisate',
            'address' => 'Avenida Castro Alves, 62 - Bairro Somenzari - Marília - SP',
            'cep' => '17506-000',
            'phone' => '(14) 3433-5467',
            'site' => 'https://devisate.cps.sp.gov.br'
        ];

        // Período de inscrição (usar configurações globais ou valores padrão)
        $registrationPeriod = [
            'start' => $settings->event_registration_start ?? '2025-10-01',
            'end' => $settings->event_registration_end ?? '2025-11-15'
        ];

        return view('landing', compact(
            'activities',
            'events',
            'organizers',
            'settings',
            'etecInfo',
            'registrationPeriod'
        ));
    }
}
