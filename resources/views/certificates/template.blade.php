<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', serif;
            background: white;
        }
        .certificate-container {
            position: relative;
            width: 100%;
            height: 100vh;
            background-image: url('{{ asset("storage/" . $template->background_image_path) }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .certificate-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #000;
            padding: 40px;
        }
        .certificate-title {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .certificate-text {
            font-size: 24px;
            line-height: 1.6;
            margin-bottom: 30px;
            max-width: 800px;
        }
        .participant-name {
            font-size: 32px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .certificate-details {
            font-size: 18px;
            margin: 20px 0;
        }
        .validation-info {
            position: absolute;
            bottom: 30px;
            right: 40px;
            font-size: 12px;
            text-align: right;
            color: #666;
        }
        .signatures {
            position: absolute;
            bottom: 80px;
            left: 40px;
            right: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-content">
            <div class="certificate-title">Certificado</div>

            <div class="certificate-text">
                Certificamos que
            </div>

            <div class="participant-name">
                {{ $participant_name }}
            </div>

            <div class="certificate-text">
                @if($certificate_type === 'Participante')
                    participou da{{ $activity_name ? ' atividade "' . $activity_name . '"' : 's atividades do evento' }},
                    totalizando {{ $total_hours }} horas de participação.
                @elseif($certificate_type === 'Ministrador')
                    ministrou a atividade "{{ $activity_name }}",
                    totalizando {{ $total_hours }} horas de trabalho como instrutor.
                @elseif($certificate_type === 'Organizador')
                    atuou como organizador do evento,
                    totalizando {{ $total_hours }} horas de organização.
                @elseif($certificate_type === 'Parceiro')
                    contribuiu como parceiro do evento GESTEC.
                @else
                    participou das atividades do evento GESTEC,
                    totalizando {{ $total_hours }} horas de participação.
                @endif
            </div>

            <div class="certificate-details">
                Emitido em {{ $issued_date }}
            </div>
        </div>

        <!-- Assinaturas -->
        <div class="signatures">
            @if($director_signature)
                <div class="signature">
                    <div class="signature-line">
                        {{ $director_signature }}<br>
                        Diretor
                    </div>
                </div>
            @endif

            @if($coordinator_signature)
                <div class="signature">
                    <div class="signature-line">
                        {{ $coordinator_signature }}<br>
                        Coordenador
                    </div>
                </div>
            @endif
        </div>

        <!-- Informações de validação -->
        <div class="validation-info">
            Código de validação: {{ $validation_code }}<br>
            Validar em: {{ $validation_url }}
        </div>
    </div>
</body>
</html>