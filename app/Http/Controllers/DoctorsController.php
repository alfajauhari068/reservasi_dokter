<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\User;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

/**
 * Controller for managing doctors
 *
 * Handles CRUD operations for doctor management including user account creation,
 * specialization assignment, and profile management.
 *
 * @refactor Confirmed PascalCase naming conventions are properly implemented
 */
class DoctorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::with('user', 'specialization')->get();
        return view('admin.doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $specializations = Specialization::all();
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

        Doctor::create($data);
        return redirect()->route('admin.doctors.index')->with('success', 'Dokter dan akun login berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        $doctor->load('user', 'specialization', 'schedules');
        return view('admin.doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        $specializations = Specialization::all();
        return view('admin.doctors.edit', compact('doctor', 'specializations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
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
    public function destroy(Doctor $doctor)
    {
        if ($doctor->photo) {
            Storage::disk('public')->delete($doctor->photo);
        }
        $doctor->delete();
        return redirect()->route('admin.doctors.index')->with('success', 'Dokter berhasil dihapus.');
    }

    /**
     * Store schedule for a doctor
     */
    public function storeSchedule(Request $request, Doctor $doctor)
    {
        $request->validate([
            'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'quota' => 'required|integer|min:1',
        ]);

        // Check for overlapping schedules
        $overlapping = Schedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', $request->day_of_week)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();

        if ($overlapping) {
            return redirect()->route('admin.doctors.show', $doctor)
                ->with('error', 'Jadwal bertabrakan dengan jadwal yang sudah ada.');
        }

        Schedule::create([
            'doctor_id' => $doctor->id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time . ':00',
            'end_time' => $request->end_time . ':00',
            'quota' => $request->quota,
        ]);

        return redirect()->route('admin.doctors.show', $doctor)
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /**
     * Update schedule for a doctor
     */
    public function updateSchedule(Request $request, Doctor $doctor, Schedule $schedule)
    {
        $request->validate([
            'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'quota' => 'required|integer|min:1',
        ]);

        // Check for overlapping schedules (excluding current)
        $overlapping = Schedule::where('doctor_id', $doctor->id)
            ->where('id', '!=', $schedule->id)
            ->where('day_of_week', $request->day_of_week)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();

        if ($overlapping) {
            return redirect()->route('admin.doctors.show', $doctor)
                ->with('error', 'Jadwal bertabrakan dengan jadwal yang sudah ada.');
        }

        $schedule->update([
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time . ':00',
            'end_time' => $request->end_time . ':00',
            'quota' => $request->quota,
        ]);

        return redirect()->route('admin.doctors.show', $doctor)
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Delete schedule for a doctor
     */
    public function destroySchedule(Doctor $doctor, Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.doctors.show', $doctor)
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}
