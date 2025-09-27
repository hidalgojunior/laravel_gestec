<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $activities = Activity::with('instructor')->get();

        return view('activities.index', compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $instructors = User::whereIn('role', ['ministrador', 'palestrante'])->get();
        $events = Event::all();
        return view('activities.create', compact('instructors', 'events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructor_id' => 'required|exists:users,id',
            'event_id' => 'nullable|exists:events,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'workload_hours' => 'required|integer|min:1',
            'total_spots' => 'required|integer|min:1',
            'has_cost' => 'boolean',
            'pix_key' => 'nullable|string|max:255',
            'proof_file_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'waiting_list_enabled' => 'boolean',
            'type' => 'required|in:palestra,minicurso,workshop,painel',
        ]);

        $data = $request->all();

        if ($request->hasFile('proof_file_path')) {
            $data['proof_file_path'] = $request->file('proof_file_path')->store('activity-proofs', 'public');
        }

        Activity::create($data);

        return redirect()->route('activities.index')->with('success', 'Atividade criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        $activity->load(['instructor', 'enrollments.user']);
        return view('activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        $instructors = User::whereIn('role', ['ministrador', 'palestrante'])->get();
        $events = Event::all();
        return view('activities.edit', compact('activity', 'instructors', 'events'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Activity $activity)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructor_id' => 'required|exists:users,id',
            'event_id' => 'nullable|exists:events,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'workload_hours' => 'required|integer|min:1',
            'total_spots' => 'required|integer|min:1',
            'has_cost' => 'boolean',
            'pix_key' => 'nullable|string|max:255',
            'proof_file_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'waiting_list_enabled' => 'boolean',
            'type' => 'required|in:palestra,minicurso,workshop,painel',
        ]);

        $data = $request->all();

        if ($request->hasFile('proof_file_path')) {
            $data['proof_file_path'] = $request->file('proof_file_path')->store('activity-proofs', 'public');
        }

        $activity->update($data);

        return redirect()->route('activities.index')->with('success', 'Atividade atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Atividade excluída com sucesso.');
    }
}
