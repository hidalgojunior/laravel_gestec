@extends('layouts.app')

@section('title', 'Validação de Certificado')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Validação de Certificado
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Digite o código de validação para verificar a autenticidade do certificado
            </p>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form id="validation-form" class="space-y-6" onsubmit="searchCertificate(event)">
                @csrf

                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">
                        Código de Validação
                    </label>
                    <div class="mt-1">
                        <input type="text"
                               name="code"
                               id="code"
                               required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Digite o código de 32 caracteres">
                    </div>
                </div>

                <div>
                    <button type="submit"
                            id="submit-btn"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span id="btn-text">Validar Certificado</span>
                        <svg id="loading-spinner" class="hidden animate-spin ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <div id="result" class="mt-6 hidden">
                <div id="success-result" class="hidden">
                    <div class="bg-green-50 border border-green-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">
                                    Certificado Válido
                                </h3>
                                <div id="certificate-details" class="mt-2 text-sm text-green-700">
                                    <!-- Certificate details will be inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="error-result" class="hidden">
                    <div class="bg-red-50 border border-red-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    Certificado Inválido
                                </h3>
                                <div id="error-message" class="mt-2 text-sm text-red-700">
                                    <!-- Error message will be inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function searchCertificate(event) {
    event.preventDefault();

    const form = document.getElementById('validation-form');
    const submitBtn = document.getElementById('submit-btn');
    const btnText = document.getElementById('btn-text');
    const loadingSpinner = document.getElementById('loading-spinner');
    const resultDiv = document.getElementById('result');
    const successResult = document.getElementById('success-result');
    const errorResult = document.getElementById('error-result');

    // Show loading state
    submitBtn.disabled = true;
    btnText.classList.add('hidden');
    loadingSpinner.classList.remove('hidden');

    // Hide previous results
    resultDiv.classList.add('hidden');
    successResult.classList.add('hidden');
    errorResult.classList.add('hidden');

    const formData = new FormData(form);

    fetch('{{ route("certificate-validation.search") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        resultDiv.classList.remove('hidden');

        if (data.valid) {
            successResult.classList.remove('hidden');
            document.getElementById('certificate-details').innerHTML = `
                <strong>Participante:</strong> ${data.certificate.participant_name}<br>
                <strong>Tipo:</strong> ${data.certificate.certificate_type}<br>
                ${data.certificate.activity_name ? `<strong>Atividade:</strong> ${data.certificate.activity_name}<br>` : ''}
                <strong>Horas:</strong> ${data.certificate.total_hours}h<br>
                <strong>Emitido em:</strong> ${data.certificate.issued_date}
            `;
        } else {
            errorResult.classList.remove('hidden');
            document.getElementById('error-message').textContent = data.message;
        }
    })
    .catch(error => {
        resultDiv.classList.remove('hidden');
        errorResult.classList.remove('hidden');
        document.getElementById('error-message').textContent = 'Erro ao validar certificado. Tente novamente.';
        console.error('Error:', error);
    })
    .finally(() => {
        // Reset loading state
        submitBtn.disabled = false;
        btnText.classList.remove('hidden');
        loadingSpinner.classList.add('hidden');
    });
}
</script>
@endsection