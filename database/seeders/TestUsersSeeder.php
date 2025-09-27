<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar colaboradores para validação de presença
        $colaborador1 = User::create([
            'name' => 'Colaborador 1',
            'full_name' => 'Ana Oliveira',
            'email' => 'ana.colaborador@gestec.com',
            'password' => bcrypt('password'),
            'role' => 'colaborador',
            'cpf' => '11111111111',
            'whatsapp' => '11999999991',
            'must_change_password' => false,
        ]);
        $colaborador1->generateUniquePin();

        $colaborador2 = User::create([
            'name' => 'Colaborador 2',
            'full_name' => 'Carlos Pereira',
            'email' => 'carlos.colaborador@gestec.com',
            'password' => bcrypt('password'),
            'role' => 'colaborador',
            'cpf' => '22222222222',
            'whatsapp' => '11999999992',
            'must_change_password' => false,
        ]);
        $colaborador2->generateUniquePin();

        // Criar participantes para teste
        $participantes = [
            ['name' => 'Participante 1', 'full_name' => 'José Santos', 'email' => 'jose.participante@gestec.com', 'cpf' => '33333333333', 'whatsapp' => '11999999993'],
            ['name' => 'Participante 2', 'full_name' => 'Maria Silva', 'email' => 'maria.participante@gestec.com', 'cpf' => '44444444444', 'whatsapp' => '11999999994'],
            ['name' => 'Participante 3', 'full_name' => 'Pedro Costa', 'email' => 'pedro.participante@gestec.com', 'cpf' => '55555555555', 'whatsapp' => '11999999995'],
            ['name' => 'Participante 4', 'full_name' => 'Lucia Ferreira', 'email' => 'lucia.participante@gestec.com', 'cpf' => '66666666666', 'whatsapp' => '11999999996'],
            ['name' => 'Participante 5', 'full_name' => 'Roberto Lima', 'email' => 'roberto.participante@gestec.com', 'cpf' => '77777777777', 'whatsapp' => '11999999997'],
        ];

        foreach ($participantes as $participanteData) {
            $participante = User::create(array_merge($participanteData, [
                'password' => bcrypt('password'),
                'role' => 'participante',
                'must_change_password' => false,
            ]));
            $participante->generateUniquePin();
        }

        // Criar um participante desqualificado para teste
        $desqualificado = User::create([
            'name' => 'Desqualificado',
            'full_name' => 'João Desqualificado',
            'email' => 'joao.desqualificado@gestec.com',
            'password' => bcrypt('password'),
            'role' => 'participante',
            'cpf' => '88888888888',
            'whatsapp' => '11999999998',
            'must_change_password' => false,
            'is_disqualified' => true,
            'disqualification_reason' => 'Falta em atividade obrigatória',
            'disqualified_at' => now(),
        ]);
        $desqualificado->generateUniquePin();
    }
}
