<?php

namespace App\Http\Controllers;

use App\Models\doctors;
use App\Models\specializations;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class DoctorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = doctors::with('user', 'specialization')->get();
        return view('admin.doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $specializations = specializations::all();
        return view('admin.doctors.create', compact('specializations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'specialization_id' => 'required|exists:specializations,id',
            'license_number' => 'required|unique:doctors',
            'experience_years' => 'required|integer|min:0',
            'consultation_fee' => 'required|numeric|min:0',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
        ]);

        // Create user first
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'dokter',
        ]);

        // Then create doctor record
        $data = $request->except(['name', 'email', 'password']);
        $data['user_id'] = $user->id;
        
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        doctors::create($data);
        return redirect()->route('admin.doctors.index')->with('success', 'Dokter dan akun login berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(doctors $doctor)
    {
        $doctor->load('user', 'specialization', 'schedules');
        return view('admin.doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(doctors $doctor)
    {
        $specializations = specializations::all();
        return view('admin.doctors.edit', compact('doctor', 'specializations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, doctors $doctor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $doctor->user_id,
            'password' => 'nullable|string|min:6',
            'specialization_id' => 'required|exists:specializations,id',
            'license_number' => 'required|unique:doctors,license_number,' . $doctor->id,
            'experience_years' => 'required|integer|min:0',
            'consultation_fee' => 'required|numeric|min:0',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
        ]);

        // Update user
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        if ($request->password) {
            $userData['password'] = Hash::make($request->password);
        }
        $doctor->user->update($userData);

        // Update doctor record
        $data = $request->except(['name', 'email', 'password']);
        if ($request->hasFile('photo')) {
            if ($doctor->photo) {
                Storage::disk('public')->delete($doctor->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $doctor->update($data);
        return redirect()->route('admin.doctors.index')->with('success', 'Dokter berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(doctors $doctor)
    {
        if ($doctor->photo) {
            Storage::disk('public')->delete($doctor->photo);
        }
        $doctor->delete();
        return redirect()->route('admin.doctors.index')->with('success', 'Dokter berhasil dihapus.');
    }
}
