<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of patients (paginated).
     */
    public function index()
    {
        $this->middleware(['auth', 'role:admin']);

        $patients = Patient::query()
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        $this->middleware(['auth', 'role:admin']);

        return view('admin.patients.create');
    }

    /**
     * Store a newly created patient.
     */
    public function store(Request $request)
    {
        $this->middleware(['auth', 'role:admin']);

        // NOTE:
        // Project requirement mentions possible nullable user_id for patients without account.
        // However, validations requested do not include user_id.
        // Therefore, this controller validates and persists only the explicitly required fields.

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female',
            'date_of_birth' => 'nullable|date',
            'blood_type' => 'nullable|string|max:3',
            'identity_number' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        $patient = Patient::create([
            'user_id' => null,
            'full_name' => $validated['full_name'],
            'gender' => $validated['gender'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'blood_type' => $validated['blood_type'] ?? null,
            'identity_number' => $validated['identity_number'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        return redirect()
            ->route('admin.patients.index')
            ->with('success', 'Pasien berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit(Patient $patient)
    {
        $this->middleware(['auth', 'role:admin']);

        return view('admin.patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $this->middleware(['auth', 'role:admin']);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female',
            'date_of_birth' => 'nullable|date',
            'blood_type' => 'nullable|string|max:3',
            'identity_number' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        $patient->update([
            'full_name' => $validated['full_name'],
            'gender' => $validated['gender'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'blood_type' => $validated['blood_type'] ?? null,
            'identity_number' => $validated['identity_number'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        return redirect()
            ->route('admin.patients.index')
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    /**
     * Remove the specified patient from storage.
     */
    public function destroy(Patient $patient)
    {
        $this->middleware(['auth', 'role:admin']);

        // Use hard delete by default (project does not mention soft deletes).
        $patient->delete();

        return redirect()
            ->route('admin.patients.index')
            ->with('success', 'Pasien berhasil dihapus.');
    }
}

