<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateValidationController extends Controller
{
    /**
     * Validar certificado por código
     */
    public function validate(Request $request, string $code)
    {
        $certificate = Certificate::where('validation_code', $code)
            ->with(['user', 'activity'])
            ->first();

        if (!$certificate) {
            return view('certificates.validation.invalid', [
                'code' => $code,
                'error' => 'Código de validação não encontrado.'
            ]);
        }

        // Verificar se certificado é válido
        if (!$certificate->isValid()) {
            return view('certificates.validation.invalid', [
                'code' => $code,
                'error' => 'Este certificado foi revogado ou o usuário foi desqualificado.'
            ]);
        }

        return view('certificates.validation.valid', compact('certificate'));
    }

    /**
     * Página de validação (formulário)
     */
    public function index()
    {
        return view('certificates.validation.index');
    }

    /**
     * Buscar certificado por código (AJAX)
     */
    public function search(Request $request)
    {
        $request->validate([
            'code' => 'required|string|min:10|max:50'
        ]);

        $certificate = Certificate::where('validation_code', $request->code)
            ->with(['user', 'activity'])
            ->first();

        if (!$certificate) {
            return response()->json([
                'valid' => false,
                'message' => 'Código de validação não encontrado.'
            ]);
        }

        if (!$certificate->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Este certificado foi revogado ou o usuário foi desqualificado.'
            ]);
        }

        return response()->json([
            'valid' => true,
            'certificate' => [
                'participant_name' => $certificate->user->full_name ?? $certificate->user->name,
                'certificate_type' => $certificate->getCertificateTypeLabel(),
                'activity_name' => $certificate->activity?->name,
                'total_hours' => $certificate->total_hours,
                'issued_date' => $certificate->issued_at->format('d/m/Y'),
                'validation_code' => $certificate->validation_code,
            ]
        ]);
    }
}
