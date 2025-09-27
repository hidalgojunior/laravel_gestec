<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar ministradores existentes
        $instructors = User::where('role', 'ministrador')->get();

        if ($instructors->isEmpty()) {
            // Criar alguns ministradores se não existirem
            $instructor1 = User::create([
                'name' => 'Dr. Silva',
                'full_name' => 'João Silva',
                'email' => 'joao.silva@gestec.com',
                'password' => bcrypt('password'),
                'role' => 'ministrador',
                'cpf' => '12345678901',
                'whatsapp' => '11999999999',
                'must_change_password' => false,
            ]);

            $instructor2 = User::create([
                'name' => 'Dra. Santos',
                'full_name' => 'Maria Santos',
                'email' => 'maria.santos@gestec.com',
                'password' => bcrypt('password'),
                'role' => 'ministrador',
                'cpf' => '12345678902',
                'whatsapp' => '11999999998',
                'must_change_password' => false,
            ]);

            $instructors = collect([$instructor1, $instructor2]);
        }

        // Criar atividades de exemplo
        $activities = [
            [
                'name' => 'Introdução à Gestão de Projetos Ágeis',
                'description' => 'Palestra sobre os fundamentos da gestão ágil de projetos, incluindo Scrum e Kanban.',
                'instructor_id' => $instructors->first()->id,
                'start_time' => now()->addDays(1)->setTime(9, 0),
                'end_time' => now()->addDays(1)->setTime(10, 30),
                'workload_hours' => 2,
                'total_spots' => 50,
                'has_cost' => false,
                'waiting_list_enabled' => true,
                'type' => 'palestra',
            ],
            [
                'name' => 'Minicurso: Desenvolvimento Web com Laravel',
                'description' => 'Minicurso prático sobre desenvolvimento de aplicações web usando o framework Laravel.',
                'instructor_id' => $instructors->last()->id,
                'start_time' => now()->addDays(1)->setTime(14, 0),
                'end_time' => now()->addDays(1)->setTime(17, 0),
                'workload_hours' => 3,
                'total_spots' => 30,
                'has_cost' => true,
                'pix_key' => 'gestec@minicurso.com',
                'waiting_list_enabled' => true,
                'type' => 'minicurso',
            ],
            [
                'name' => 'Workshop: UX/UI Design Thinking',
                'description' => 'Workshop interativo sobre design thinking aplicado a interfaces digitais.',
                'instructor_id' => $instructors->first()->id,
                'start_time' => now()->addDays(2)->setTime(9, 0),
                'end_time' => now()->addDays(2)->setTime(12, 0),
                'workload_hours' => 3,
                'total_spots' => 25,
                'has_cost' => false,
                'waiting_list_enabled' => true,
                'type' => 'workshop',
            ],
            [
                'name' => 'Painel: Tendências em Tecnologia da Informação',
                'description' => 'Debate com especialistas sobre as principais tendências em TI para os próximos anos.',
                'instructor_id' => $instructors->last()->id,
                'start_time' => now()->addDays(2)->setTime(14, 0),
                'end_time' => now()->addDays(2)->setTime(16, 0),
                'workload_hours' => 2,
                'total_spots' => 40,
                'has_cost' => false,
                'waiting_list_enabled' => true,
                'type' => 'painel',
            ],
        ];

        foreach ($activities as $activityData) {
            Activity::create($activityData);
        }
    }
}
