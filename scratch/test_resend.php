<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;

// Enviar correo de prueba
$destinatario = 'jemeo29@gmail.com'; // Gmail del admin para probar

echo "Enviando correo de prueba via Resend a: $destinatario\n";

try {
    Mail::raw(
        "✅ Prueba de correo desde Tareas App\n\nSi ves este mensaje, Resend está funcionando correctamente.\n\nFecha: " . now()->format('d/m/Y H:i:s'),
        function ($message) use ($destinatario) {
            $message->to($destinatario)
                    ->subject('✅ Prueba Resend - Tareas App [' . now()->format('H:i:s') . ']');
        }
    );
    echo "✅ ¡Correo enviado con éxito! Revisa tu Gmail: $destinatario\n";
} catch (\Exception $e) {
    echo "❌ Error al enviar: " . $e->getMessage() . "\n";
    echo "\nDetalle del error:\n";
    echo $e->getTraceAsString() . "\n";
}
