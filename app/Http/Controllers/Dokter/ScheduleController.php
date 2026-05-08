<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:dokter']);
    }

    public function index()
    {
        $doctor = Doctor::where('user_id', auth()->id())
            ->with('schedules')
            ->first();

        if (! $doctor) {
            return redirect()->route('dokter.dashboard')
                ->with('error', 'Profil dokter tidak ditemukan.');
        }

        $schedules = $doctor->schedules()->orderBy('day_of_week')->orderBy('start_time')->get();

        return view('dokter.schedule.index', compact('schedules'));
    }

    public function create()
    {
        return view('dokter.schedule.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $doctor = Doctor::where('user_id', auth()->id())->first();
        if (! $doctor) {
            return redirect()->route('dokter.dashboard')
                ->with('error', 'Profil dokter tidak ditemukan.');
        }

        // Check for overlapping schedules
        $overlapping = $doctor->schedules()
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
            return back()->withErrors(['time' => 'Jadwal bertabrakan dengan jadwal yang sudah ada.'])->withInput();
        }

        $doctor->schedules()->create([
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('dokter.schedule.index')->with('success', 'Schedule berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule)
    {
        $doctor = Doctor::where('user_id', auth()->id())->first();
        if (! $doctor || $schedule->doctor_id !== $doctor->id) {
            abort(403);
        }

        return view('dokter.schedule.edit', compact('schedule'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $doctor = Doctor::where('user_id', auth()->id())->first();
        if (! $doctor || $schedule->doctor_id !== $doctor->id) {
            abort(403);
        }

        // Check for overlapping schedules (excluding current)
        $overlapping = $doctor->schedules()
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
            return back()->withErrors(['time' => 'Jadwal bertabrakan dengan jadwal yang sudah ada.'])->withInput();
        }

        $schedule->update([
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('dokter.schedule.index')->with('success', 'Schedule berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $doctor = Doctor::where('user_id', auth()->id())->first();
        if (! $doctor || $schedule->doctor_id !== $doctor->id) {
            abort(403);
        }

        $schedule->delete();

        return redirect()->route('dokter.schedule.index')->with('success', 'Schedule berhasil dihapus.');
    }
}