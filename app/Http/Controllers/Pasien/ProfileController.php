<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:pasien']);
    }

    public function edit()
    {
        $patient = Patient::where('user_id', auth()->id())->first();

        return view('pasien.profile', [
            'patient' => $patient,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female',
            'date_of_birth' => 'nullable|date',
            'blood_type' => 'nullable|string|max:3',
            'identity_number' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
        ]);


        Patient::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'full_name' => $validated['full_name'],
                'gender' => $validated['gender'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'blood_type' => $validated['blood_type'] ?? null,
                'identity_number' => $validated['identity_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'phone' => $validated['phone_number'] ?? null,

            ]
        );

        return redirect()->route('pasien.dashboard')
            ->with('success', 'Profil pasien berhasil disimpan.');
    }
}
