<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\GlobalSetting;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function __construct()
    {
        // Middleware será definido nas rotas
    }

    /**
     * Mostrar certificados do usuário logado
     */
    public function index()
    {
        $user = Auth::user();
        $certificates = Certificate::where('user_id', $user->id)
            ->with(['activity'])
            ->orderBy('issued_at', 'desc')
            ->get();

        return view('certificates.index', compact('certificates'));
    }

    /**
     * Gerar certificado para o usuário
     */
    public function generate(Request $request)
    {
        $user = Auth::user();

        // Verificar se usuário pode gerar certificado
        if ($user->isDisqualified()) {
            return back()->with('error', 'Você foi desqualificado e não pode gerar certificados.');
        }

        $activityId = $request->get('activity_id');

        // Verificar se já existe certificado
        $existingCertificate = Certificate::where('user_id', $user->id)
            ->when($activityId, fn($q) => $q->where('activity_id', $activityId))
            ->first();

        if ($existingCertificate) {
            return $this->downloadCertificate($existingCertificate);
        }

        // Calcular carga horária
        $activity = $activityId ? Activity::find($activityId) : null;
        $totalHours = Certificate::calculateHours($user, $activity);

        // Para parceiros, sempre gerar certificado de agradecimento
        if ($user->isParceiro()) {
            $totalHours = 0;
        }

        // Verificar se há horas suficientes (exceto para parceiros)
        if (!$user->isParceiro() && $totalHours <= 0) {
            return back()->with('error', 'Você não possui horas suficientes para gerar um certificado.');
        }

        // Criar certificado
        $certificate = Certificate::create([
            'user_id' => $user->id,
            'activity_id' => $activityId,
            'certificate_type' => $this->getCertificateType($user),
            'total_hours' => $totalHours,
            'validation_code' => Certificate::generateValidationCode(),
            'certificate_data' => [], // Será preenchido na geração do PDF
            'issued_at' => now(),
        ]);

        // Gerar PDF
        return $this->generateCertificatePDF($certificate);
    }

    /**
     * Download de certificado existente
     */
    public function download(Certificate $certificate)
    {
        $user = Auth::user();

        // Verificar se o certificado pertence ao usuário
        if ($certificate->user_id !== $user->id) {
            return back()->with('error', 'Acesso negado.');
        }

        return $this->downloadCertificate($certificate);
    }

    /**
     * Obter tipo de certificado baseado no role do usuário
     */
    private function getCertificateType(User $user): string
    {
        return match($user->role) {
            'participante' => 'participant',
            'colaborador' => 'collaborator',
            'ministrador' => 'instructor',
            'organizador' => 'organizer',
            'parceiro' => 'partner',
            default => 'participant',
        };
    }

    /**
     * Gerar PDF do certificado
     */
    private function generateCertificatePDF(Certificate $certificate)
    {
        // Obter template
        $template = CertificateTemplate::getTemplateForActivity($certificate->activity);

        if (!$template) {
            return back()->with('error', 'Nenhum template de certificado configurado.');
        }

        // Preparar dados
        $data = $certificate->generateCertificateData();
        $data['template'] = $template;

        // Gerar PDF
        $pdf = Pdf::loadView('certificates.template', $data);
        $pdf->setPaper('a4', 'landscape');

        // Salvar PDF
        $filename = 'certificate_' . $certificate->validation_code . '.pdf';
        $path = 'certificates/' . $filename;
        Storage::disk('public')->put($path, $pdf->output());

        // Atualizar certificado com caminho do PDF
        $certificate->update([
            'pdf_path' => $path,
            'certificate_data' => $data,
        ]);

        // Download
        return $pdf->download($filename);
    }

    /**
     * Download de certificado existente
     */
    private function downloadCertificate(Certificate $certificate)
    {
        if (!$certificate->pdf_path || !Storage::disk('public')->exists($certificate->pdf_path)) {
            // Regenerar PDF se não existir
            return $this->generateCertificatePDF($certificate);
        }

        $filename = 'certificate_' . $certificate->validation_code . '.pdf';
        $path = Storage::disk('public')->path($certificate->pdf_path);
        return response()->download($path, $filename);
    }

    /**
     * Mostrar página de administração de certificados
     */
    public function admin()
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isOrganizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $certificates = Certificate::with(['user', 'activity'])
            ->orderBy('issued_at', 'desc')
            ->paginate(20);

        return view('certificates.admin', compact('certificates'));
    }

    /**
     * Revogar certificado
     */
    public function revoke(Certificate $certificate)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isOrganizador()) {
            return back()->with('error', 'Acesso negado.');
        }

        // Remover arquivo PDF
        if ($certificate->pdf_path) {
            Storage::disk('public')->delete($certificate->pdf_path);
        }

        $certificate->delete();

        return back()->with('success', 'Certificado revogado com sucesso.');
    }
}
