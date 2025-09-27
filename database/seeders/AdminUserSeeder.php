<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin especial - não pode ser excluído
        \App\Models\User::firstOrCreate(
            ['email' => 'arnaldo@hidalgojunior.com.br'],
            [
                'name' => 'Arnaldo Hidalgo Junior',
                'password' => bcrypt('admin123'),
                'must_change_password' => false, // Admin não precisa mudar senha
                'is_protected' => true,
            ]
        );

        // Usuários padrão com senha gestec2025
        $defaultUsers = [
            ['name' => 'Administrador', 'email' => 'admin@gestec.com.br'],
            ['name' => 'Usuário Teste', 'email' => 'user@gestec.com.br'],
        ];

        foreach ($defaultUsers as $userData) {
            \App\Models\User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => bcrypt('gestec2025'),
                    'must_change_password' => true, // Devem alterar senha no primeiro acesso
                    'is_protected' => false,
                ]
            );
        }
    }
}
