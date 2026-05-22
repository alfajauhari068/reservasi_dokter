<?php

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// TEMP: Fix bcrypt hash for legacy/plaintext passwords.
// Use once, then delete both this file and the route registration below.

Route::get('/debug/fix-password-hash-once', function () {
    $updated = [
        'admin' => 0,
        'dokter' => 0,
        'pasien' => 0,
    ];

    $updated['admin'] = User::where('role', User::ROLE_ADMIN)
        ->update(['password' => Hash::make('admin123')]);

    $updated['dokter'] = User::where('role', User::ROLE_DOKTER)
        ->update(['password' => Hash::make('dokter123')]);

    $updated['pasien'] = User::where('role', User::ROLE_PASIEN)
        ->update(['password' => Hash::make('pasien123')]);

    return response()->json([
        'status' => 'ok',
        'updated' => $updated,
        'message' => 'Route bersifat sementara. Hapus route ini setelah berhasil.'
    ]);
});

