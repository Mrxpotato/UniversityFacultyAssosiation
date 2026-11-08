<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTrainingSeminarRequest;
use App\Http\Requests\UpdateTrainingSeminarRequest;
use App\Models\Campus;
use App\Models\TrainingRegistration;
use App\Models\TrainingSeminar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrainingSeminarController extends Controller
{
    /**
     * Display a listing of the resource (public calendar view).
     */
    public function index(Request $request)
    {
        $query = TrainingSeminar::with(['campus'])
            ->published();

        // Apply filters
        if ($request->type) {
            $query->ofType($request->type);
        }

        if ($request->campus_id) {
            $query->forCampus($request->campus_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $trainings = $query->orderBy('start_datetime')->paginate(12);
        $types = TrainingSeminar::TYPES;
        $statuses = TrainingSeminar::STATUSES;
        $campuses = Campus::active()->get();

        return view('trainings.index', compact('trainings', 'types', 'statuses', 'campuses'));
    }

    /**
     * Display upcoming trainings for calendar.
     */
    public function calendar(Request $request)
    {
        // If it's an AJAX request or expecting JSON, return calendar data
        if ($request->wantsJson() || $request->ajax()) {
            $start = $request->get('start', now()->startOfMonth());
            $end = $request->get('end', now()->endOfMonth());

            $trainings = TrainingSeminar::published()
                ->whereBetween('start_datetime', [$start, $end])
                ->get()
                ->map(function ($training) {
                    return [
                        'id' => $training->id,
                        'title' => $training->title,
                        'start' => $training->start_datetime->toIso8601String(),
                        'end' => $training->end_datetime->toIso8601String(),
                        'url' => route('trainings.show', $training),
                        'backgroundColor' => $this->getTypeColor($training->type),
                        'extendedProps' => [
                            'type' => $training->type_label,
                            'venue' => $training->venue,
                            'status' => $training->status,
                        ],
                    ];
                });

            return response()->json($trainings);
        }

        // Return calendar view
        return view('trainings.calendar');
    }

    /**
     * Display the specified resource (public).
     */
    public function show(TrainingSeminar $trainingSeminar)
    {
        $this->authorize('view', $trainingSeminar);

        $trainingSeminar->load(['campus', 'author', 'registrations.facultyProfile.user']);

        $isRegistered = false;
        $user = auth()->user();
        if ($user && $user->facultyProfile) {
            $isRegistered = $trainingSeminar->registrations()
                ->where('faculty_profile_id', $user->facultyProfile->id)
                ->exists();
        }

        return view('trainings.show', compact('trainingSeminar', 'isRegistered'));
    }

    /**
     * Register for a training.
     */
    public function register(TrainingSeminar $trainingSeminar)
    {
        $this->authorize('register', $trainingSeminar);

        $user = auth()->user();
        $profile = $user->facultyProfile;

        // Check if already registered
        $existing = TrainingRegistration::where('training_seminar_id', $trainingSeminar->id)
            ->where('faculty_profile_id', $profile->id)
            ->first();

        if ($existing) {
            return redirect()
                ->route('trainings.show', $trainingSeminar)
                ->with('error', 'You are already registered for this training.');
        }

        TrainingRegistration::create([
            'training_seminar_id' => $trainingSeminar->id,
            'faculty_profile_id' => $profile->id,
            'status' => 'registered',
        ]);

        return redirect()
            ->route('trainings.show', $trainingSeminar)
            ->with('success', 'You have been registered successfully.');
    }

    /**
     * Cancel registration.
     */
    public function cancelRegistration(TrainingSeminar $trainingSeminar)
    {
        $user = auth()->user();
        $profile = $user->facultyProfile;

        if (! $profile) {
            return redirect()->route('trainings.show', $trainingSeminar)
                ->with('error', 'No faculty profile found.');
        }

        $registration = TrainingRegistration::where('training_seminar_id', $trainingSeminar->id)
            ->where('faculty_profile_id', $profile->id)
            ->first();

        if ($registration) {
            $registration->update(['status' => 'cancelled']);
        }

        return redirect()
            ->route('trainings.show', $trainingSeminar)
            ->with('success', 'Your registration has been cancelled.');
    }

    /**
     * Display admin listing.
     */
    public function adminIndex(Request $request)
    {
        $this->authorize('create', TrainingSeminar::class);

        $trainings = TrainingSeminar::with(['campus', 'author'])
            ->when($request->type, fn ($q, $t) => $q->ofType($t))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->search, function ($q, $s) {
                $q->where('title', 'like', "%{$s}%");
            })
            ->latest()
            ->paginate(15);

        $types = TrainingSeminar::TYPES;
        $statuses = TrainingSeminar::STATUSES;

        return view('admin.trainings.index', compact('trainings', 'types', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', TrainingSeminar::class);

        $types = TrainingSeminar::TYPES;
        $campuses = Campus::active()->get();

        return view('admin.trainings.create', compact('types', 'campuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTrainingSeminarRequest $request)
    {
        $this->authorize('create', TrainingSeminar::class);

        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);

        TrainingSeminar::create($data);

        return redirect()
            ->route('admin.trainings.index')
            ->with('success', 'Training/Seminar created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TrainingSeminar $trainingSeminar)
    {
        $this->authorize('update', $trainingSeminar);

        $types = TrainingSeminar::TYPES;
        $statuses = TrainingSeminar::STATUSES;
        $campuses = Campus::active()->get();

        return view('admin.trainings.edit', compact('trainingSeminar', 'types', 'statuses', 'campuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTrainingSeminarRequest $request, TrainingSeminar $trainingSeminar)
    {
        $this->authorize('update', $trainingSeminar);

        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);

        $trainingSeminar->update($data);

        return redirect()
            ->route('admin.trainings.index')
            ->with('success', 'Training/Seminar updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrainingSeminar $trainingSeminar)
    {
        $this->authorize('delete', $trainingSeminar);

        $trainingSeminar->delete();

        return redirect()
            ->route('admin.trainings.index')
            ->with('success', 'Training/Seminar deleted successfully.');
    }

    /**
     * View registrations for a training.
     */
    public function registrations(TrainingSeminar $trainingSeminar)
    {
        $this->authorize('update', $trainingSeminar);

        $registrations = $trainingSeminar->registrations()
            ->with(['facultyProfile.user', 'facultyProfile.campus'])
            ->get();

        return view('admin.trainings.registrations', compact('trainingSeminar', 'registrations'));
    }

    /**
     * Update registration status.
     */
    public function updateRegistration(Request $request, TrainingRegistration $registration)
    {
        $this->authorize('update', $registration->trainingSeminar);

        $request->validate([
            'status' => 'required|in:registered,confirmed,attended,cancelled',
        ]);

        $registration->update(['status' => $request->status]);

        return redirect()
            ->back()
            ->with('success', 'Registration status updated.');
    }

    /**
     * Get color based on training type.
     */
    protected function getTypeColor(string $type): string
    {
        return match ($type) {
            'training' => '#4CAF50',
            'seminar' => '#2196F3',
            'workshop' => '#FF9800',
            'conference' => '#9C27B0',
            'webinar' => '#00BCD4',
            default => '#607D8B',
        };
    }
}
