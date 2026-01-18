<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Simula una richiesta GET /spese/1/edit
$request = Illuminate\Http\Request::create('/spese/1/edit', 'GET');
$response = $kernel->handle($request);

echo "=== RESPONSE STATUS ===" . PHP_EOL;
echo "Status: " . $response->getStatusCode() . PHP_EOL;
echo PHP_EOL;

echo "=== RESPONSE CONTENT (primi 500 char) ===" . PHP_EOL;
$content = $response->getContent();
echo substr($content, 0, 500) . PHP_EOL;
echo PHP_EOL;

// Cerca i props Inertia nel content
if (preg_match('/"props":\{.*?"spesa":\{(.*?)\}/', $content, $matches)) {
    echo "=== PROPS SPESA TROVATI ===" . PHP_EOL;
    echo $matches[0] . PHP_EOL;
} else {
    echo "❌ PROPS SPESA NON TROVATI NEL RESPONSE!" . PHP_EOL;
}

$kernel->terminate($request, $response);
