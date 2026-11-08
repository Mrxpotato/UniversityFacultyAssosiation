<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resolution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResolutionController extends Controller
{
    /**
     * Display a listing of resolutions.
     */
    public function index(Request $request)
    {
        $query = Resolution::with('author')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->search, function ($q, $s) {
                $q->where(function ($query) use ($s) {
                    $query->where('title', 'like', "%{$s}%")
                        ->orWhere('resolution_number', 'like', "%{$s}%")
                        ->orWhere('content', 'like', "%{$s}%");
                });
            });

        $resolutions = $query->latest()->paginate(15);

        return view('admin.transparency.resolutions.index', [
            'resolutions' => $resolutions,
            'statuses' => Resolution::STATUSES,
        ]);
    }

    /**
     * Show the form for creating a new resolution.
     */
    public function create()
    {
        return view('admin.transparency.resolutions.create', [
            'nextResolutionNumber' => Resolution::generateResolutionNumber(),
        ]);
    }

    /**
     * Store a newly created resolution.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'resolved_date' => 'required|date',
            'votes_for' => 'required|integer|min:0',
            'votes_against' => 'required|integer|min:0',
            'votes_abstain' => 'required|integer|min:0',
            'status' => 'required|in:'.implode(',', array_keys(Resolution::STATUSES)),
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif',
        ]);

        $validated['created_by'] = auth()->id();

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('resolutions', 'public');
                $attachments[] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ];
            }
            $validated['attachments'] = $attachments;
        }

        // Set published_at if publishing
        if ($validated['status'] === 'Published') {
            $validated['published_at'] = now();
        }

        Resolution::create($validated);

        return redirect()
            ->route('admin.transparency.resolutions.index')
            ->with('success', 'Resolution created successfully.');
    }

    /**
     * Display the specified resolution.
     */
    public function show(Resolution $resolution)
    {
        $resolution->load('author');

        return view('admin.transparency.resolutions.show', [
            'resolution' => $resolution,
        ]);
    }

    /**
     * Show the form for editing the specified resolution.
     */
    public function edit(Resolution $resolution)
    {
        return view('admin.transparency.resolutions.edit', [
            'resolution' => $resolution,
            'statuses' => Resolution::STATUSES,
        ]);
    }

    /**
     * Update the specified resolution.
     */
    public function update(Request $request, Resolution $resolution)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'resolved_date' => 'required|date',
            'votes_for' => 'required|integer|min:0',
            'votes_against' => 'required|integer|min:0',
            'votes_abstain' => 'required|integer|min:0',
            'status' => 'required|in:'.implode(',', array_keys(Resolution::STATUSES)),
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif',
        ]);

        // Handle new file uploads
        if ($request->hasFile('attachments')) {
            $attachments = $resolution->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('resolutions', 'public');
                $attachments[] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ];
            }
            $validated['attachments'] = $attachments;
        }

        // Set published_at if publishing for the first time
        if ($validated['status'] === 'Published' && ! $resolution->published_at) {
            $validated['published_at'] = now();
        }

        $resolution->update($validated);

        return redirect()
            ->route('admin.transparency.resolutions.index')
            ->with('success', 'Resolution updated successfully.');
    }

    /**
     * Remove the specified resolution.
     */
    public function destroy(Resolution $resolution)
    {
        // Delete attachments
        if ($resolution->attachments) {
            foreach ($resolution->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $resolution->delete();

        return redirect()
            ->route('admin.transparency.resolutions.index')
            ->with('success', 'Resolution deleted successfully.');
    }

    /**
     * Remove an attachment from a resolution.
     */
    public function removeAttachment(Request $request, Resolution $resolution)
    {
        $request->validate(['index' => 'required|integer|min:0']);
        $index = (int) $request->input('index');
        $attachments = $resolution->attachments ?? [];

        if (isset($attachments[$index])) {
            Storage::disk('public')->delete($attachments[$index]['path']);
            unset($attachments[$index]);
            $resolution->update(['attachments' => array_values($attachments)]);
        }

        return back()->with('success', 'Attachment removed.');
    }

    /**
     * Toggle publish status.
     */
    public function togglePublish(Resolution $resolution)
    {
        if ($resolution->status === 'Published') {
            $resolution->update(['status' => 'Draft']);
            $message = 'Resolution unpublished.';
        } else {
            $resolution->update([
                'status' => 'Published',
                'published_at' => $resolution->published_at ?? now(),
            ]);
            $message = 'Resolution published.';
        }

        return back()->with('success', $message);
    }
}
