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
            'theme' => 'required|in:light,dark',
            'homepage_title' => 'required|string|max:255',
            'homepage_subtitle' => 'nullable|string|max:255',
            'homepage_description' => 'nullable|string',
            'homepage_features' => 'nullable|array',
            'homepage_features.*' => 'string|max:255',
            'homepage_cta_text' => 'required|string|max:255',
            'homepage_cta_link' => 'required|string|max:255',
            'certificate_director_signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'certificate_coordinator_signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'certificate_text_template' => 'nullable|string',
            'validation_base_url' => 'nullable|url',
        ]);

        $settings = GlobalSetting::getSettings();

        $data = [
            'project_name' => $request->project_name,
            'description' => $request->description,
            'theme' => $request->theme,
            'homepage_title' => $request->homepage_title,
            'homepage_subtitle' => $request->homepage_subtitle,
            'homepage_description' => $request->homepage_description,
            'homepage_features' => array_filter(explode("\n", $request->homepage_features_text ?? '')),
            'homepage_cta_text' => $request->homepage_cta_text,
            'homepage_cta_link' => $request->homepage_cta_link,
            'certificate_text_template' => $request->certificate_text_template,
            'validation_base_url' => $request->validation_base_url,
        ];

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $data['logo_path'] = $logoPath;
        }

        if ($request->hasFile('certificate_director_signature')) {
            $directorSignaturePath = $request->file('certificate_director_signature')->store('signatures', 'public');
            $data['certificate_director_signature'] = $directorSignaturePath;
        }

        if ($request->hasFile('certificate_coordinator_signature')) {
            $coordinatorSignaturePath = $request->file('certificate_coordinator_signature')->store('signatures', 'public');
            $data['certificate_coordinator_signature'] = $coordinatorSignaturePath;
        }

        $settings->update($data);

        return redirect()->back()->with('success', 'Configurações atualizadas com sucesso!');
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
