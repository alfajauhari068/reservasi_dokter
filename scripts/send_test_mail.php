<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mailer = $app->make('mailer');

try {
    $mailer->raw('Ini adalah email uji dari aplikasi (Resend). Jika Anda menerimanya, konfigurasi mailer Resend berhasil.', function ($message) {
        $message->to('alfajauhari068@gmail.com')->subject('Uji Mailer Resend');
    });
    echo "Mail send attempted\n";
} catch (\Exception $e) {
    echo "Error sending mail: " . $e->getMessage() . "\n";
}
