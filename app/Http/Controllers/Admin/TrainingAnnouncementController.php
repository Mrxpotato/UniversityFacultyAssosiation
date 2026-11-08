<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingAnnouncement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrainingAnnouncementController extends Controller
{
    /**
     * Display a listing of training announcements.
     */
    public function index(Request $request)
    {
        $query = TrainingAnnouncement::with('author')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->training_type, fn ($q, $t) => $q->where('training_type', $t))
            ->when($request->mode, fn ($q, $m) => $q->where('mode', $m))
            ->when($request->search, function ($q, $s) {
                $q->where(function ($query) use ($s) {
                    $query->where('title', 'like', "%{$s}%")
                        ->orWhere('training_number', 'like', "%{$s}%")
                        ->orWhere('speaker_facilitator', 'like', "%{$s}%");
                });
            });

        $trainings = $query->latest()->paginate(15);

        return view('admin.transparency.trainings.index', [
            'trainings' => $trainings,
            'trainingTypes' => TrainingAnnouncement::TRAINING_TYPES,
            'modes' => TrainingAnnouncement::MODES,
            'statuses' => TrainingAnnouncement::STATUSES,
        ]);
    }

    /**
     * Show the form for creating a new training announcement.
     */
    public function create()
    {
        return view('admin.transparency.trainings.create', [
            'trainingTypes' => TrainingAnnouncement::TRAINING_TYPES,
            'modes' => TrainingAnnouncement::MODES,
            'statuses' => TrainingAnnouncement::STATUSES,
            'nextTrainingNumber' => TrainingAnnouncement::generateTrainingNumber(),
        ]);
    }

    /**
     * Store a newly created training announcement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'training_type' => 'required|in:'.implode(',', array_keys(TrainingAnnouncement::TRAINING_TYPES)),
            'speaker_facilitator' => 'required|string|max:255',
            'speaker_bio' => 'nullable|string',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
            'venue' => 'required|string|max:255',
            'mode' => 'required|in:'.implode(',', array_keys(TrainingAnnouncement::MODES)),
            'max_participants' => 'nullable|integer|min:1',
            'registration_deadline' => 'nullable|date|before:date_start',
            'registration_fee' => 'nullable|numeric|min:0',
            'target_participants' => 'nullable|string|max:255',
            'learning_objectives' => 'nullable|string',
            'requirements' => 'nullable|string',
            'contact_person' => 'nullable|string|max:100',
            'contact_details' => 'nullable|string|max:255',
            'status' => 'required|in:'.implode(',', array_keys(TrainingAnnouncement::STATUSES)),
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['registration_fee'] = $validated['registration_fee'] ?? 0;

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('trainings', 'public');
                $attachments[] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ];
            }
            $validated['attachments'] = $attachments;
        }

        // Set published_at if not draft
        if ($validated['status'] !== 'Draft') {
            $validated['published_at'] = now();
        }

        TrainingAnnouncement::create($validated);

        return redirect()
            ->route('admin.transparency.trainings.index')
            ->with('success', 'Training announcement created successfully.');
    }

    /**
     * Display the specified training announcement.
     */
    public function show(TrainingAnnouncement $training)
    {
        $training->load('author');

        return view('admin.transparency.trainings.show', [
            'training' => $training,
        ]);
    }

    /**
     * Show the form for editing the specified training announcement.
     */
    public function edit(TrainingAnnouncement $training)
    {
        return view('admin.transparency.trainings.edit', [
            'training' => $training,
            'trainingTypes' => TrainingAnnouncement::TRAINING_TYPES,
            'modes' => TrainingAnnouncement::MODES,
            'statuses' => TrainingAnnouncement::STATUSES,
        ]);
    }

    /**
     * Update the specified training announcement.
     */
    public function update(Request $request, TrainingAnnouncement $training)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'training_type' => 'required|in:'.implode(',', array_keys(TrainingAnnouncement::TRAINING_TYPES)),
            'speaker_facilitator' => 'required|string|max:255',
            'speaker_bio' => 'nullable|string',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
            'venue' => 'required|string|max:255',
            'mode' => 'required|in:'.implode(',', array_keys(TrainingAnnouncement::MODES)),
            'max_participants' => 'nullable|integer|min:1',
            'registration_deadline' => 'nullable|date|before:date_start',
            'registration_fee' => 'nullable|numeric|min:0',
            'target_participants' => 'nullable|string|max:255',
            'learning_objectives' => 'nullable|string',
            'requirements' => 'nullable|string',
            'contact_person' => 'nullable|string|max:100',
            'contact_details' => 'nullable|string|max:255',
            'status' => 'required|in:'.implode(',', array_keys(TrainingAnnouncement::STATUSES)),
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif',
        ]);

        $validated['registration_fee'] = $validated['registration_fee'] ?? 0;

        // Handle new file uploads
        if ($request->hasFile('attachments')) {
            $attachments = $training->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('trainings', 'public');
                $attachments[] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ];
            }
            $validated['attachments'] = $attachments;
        }

        // Set published_at if publishing for the first time
        if ($validated['status'] !== 'Draft' && ! $training->published_at) {
            $validated['published_at'] = now();
        }

        $training->update($validated);

        return redirect()
            ->route('admin.transparency.trainings.index')
            ->with('success', 'Training announcement updated successfully.');
    }

    /**
     * Remove the specified training announcement.
     */
    public function destroy(TrainingAnnouncement $training)
    {
        // Delete attachments
        if ($training->attachments) {
            foreach ($training->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $training->delete();

        return redirect()
            ->route('admin.transparency.trainings.index')
            ->with('success', 'Training announcement deleted successfully.');
    }

    /**
     * Remove an attachment from a training.
     */
    public function removeAttachment(Request $request, TrainingAnnouncement $training)
    {
        $request->validate(['index' => 'required|integer|min:0']);
        $index = (int) $request->input('index');
        $attachments = $training->attachments ?? [];

        if (isset($attachments[$index])) {
            Storage::disk('public')->delete($attachments[$index]['path']);
            unset($attachments[$index]);
            $training->update(['attachments' => array_values($attachments)]);
        }

        return back()->with('success', 'Attachment removed.');
    }

    /**
     * Update status.
     */
    public function updateStatus(Request $request, TrainingAnnouncement $training)
    {
        $validated = $request->validate([
            'status' => 'required|in:'.implode(',', array_keys(TrainingAnnouncement::STATUSES)),
        ]);

        if ($validated['status'] !== 'Draft' && ! $training->published_at) {
            $validated['published_at'] = now();
        }

        $training->update($validated);

        return back()->with('success', 'Status updated successfully.');
    }
}
