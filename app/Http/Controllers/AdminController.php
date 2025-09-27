<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function settings()
    {
        $settings = GlobalSetting::getSettings();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $settings = GlobalSetting::getSettings();

        $data = [
            'project_name' => $request->project_name,
            'description' => $request->description,
        ];

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $data['logo_path'] = $logoPath;
        }

        $settings->update($data);

        return redirect()->back()->with('success', 'Configurações atualizadas com sucesso!');
    }

    public function maintenance()
    {
        $settings = GlobalSetting::getSettings();
        return view('admin.maintenance', compact('settings'));
    }

    public function toggleMaintenance(Request $request)
    {
        $request->validate([
            'maintenance_mode' => 'required|boolean',
            'maintenance_message' => 'nullable|string|max:500',
        ]);

        $settings = GlobalSetting::getSettings();

        if ($request->maintenance_mode) {
            $settings->enableMaintenanceMode($request->maintenance_message);
            $message = 'Modo de manutenção ativado com sucesso!';
        } else {
            $settings->disableMaintenanceMode();
            $message = 'Modo de manutenção desativado com sucesso!';
        }

        return redirect()->back()->with('success', $message);
    }

    public function backupDatabase()
    {
        try {
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $path = storage_path('app/backups/' . $filename);

            // Criar diretório se não existir
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }

            // Comando mysqldump (assumindo Windows com mysqldump disponível)
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s --port=%s %s > %s',
                escapeshellarg(env('DB_USERNAME')),
                escapeshellarg(env('DB_PASSWORD')),
                escapeshellarg(env('DB_HOST')),
                escapeshellarg(env('DB_PORT', 3306)),
                escapeshellarg(env('DB_DATABASE')),
                escapeshellarg($path)
            );

            exec($command, $output, $returnCode);

            if ($returnCode === 0) {
                return response()->download($path)->deleteFileAfterSend();
            } else {
                return redirect()->back()->with('error', 'Erro ao criar backup do banco de dados.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro: ' . $e->getMessage());
        }
    }

    public function restoreDatabase(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql|max:51200', // 50MB max
        ]);

        try {
            $file = $request->file('backup_file');
            $path = $file->getRealPath();

            // Comando mysql para restaurar
            $command = sprintf(
                'mysql --user=%s --password=%s --host=%s --port=%s %s < %s',
                escapeshellarg(env('DB_USERNAME')),
                escapeshellarg(env('DB_PASSWORD')),
                escapeshellarg(env('DB_HOST')),
                escapeshellarg(env('DB_PORT', 3306)),
                escapeshellarg(env('DB_DATABASE')),
                escapeshellarg($path)
            );

            exec($command, $output, $returnCode);

            if ($returnCode === 0) {
                return redirect()->back()->with('success', 'Backup restaurado com sucesso!');
            } else {
                return redirect()->back()->with('error', 'Erro ao restaurar backup.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro: ' . $e->getMessage());
        }
    }
}
