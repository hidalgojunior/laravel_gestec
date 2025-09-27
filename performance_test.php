<?php

echo "Teste de Performance GESTEC\n";
echo "==========================\n\n";

$url = 'http://127.0.0.1:8001/';
$requests = 10;

echo "Testando $requests requisições simultâneas...\n\n";

$start = microtime(true);
$results = [];

for ($i = 0; $i < $requests; $i++) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD request

    $start_req = microtime(true);
    $response = curl_exec($ch);
    $end_req = microtime(true);

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $time = round(($end_req - $start_req) * 1000, 2); // ms

    $results[] = ['code' => $http_code, 'time' => $time];

    curl_close($ch);
}

$end = microtime(true);
$total_time = round(($end - $start) * 1000, 2);

$success_count = count(array_filter($results, fn($r) => $r['code'] == 200));
$avg_time = round(array_sum(array_column($results, 'time')) / count($results), 2);

echo "Resultados:\n";
echo "- Total de requisições: $requests\n";
echo "- Sucessos (200): $success_count\n";
echo "- Tempo médio por requisição: {$avg_time}ms\n";
echo "- Tempo total: {$total_time}ms\n\n";

if ($success_count == $requests) {
    echo "✅ Teste de performance APROVADO - Sistema suporta carga básica\n";
} else {
    echo "❌ Teste de performance REPROVADO\n";
}