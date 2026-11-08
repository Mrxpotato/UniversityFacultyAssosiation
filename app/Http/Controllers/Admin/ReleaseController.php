<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Release;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReleaseController extends Controller
{
    /**
     * Display a listing of releases.
     */
    public function index(Request $request)
    {
        $query = Release::with('author')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->release_type, fn ($q, $t) => $q->where('release_type', $t))
            ->when($request->search, function ($q, $s) {
                $q->where(function ($query) use ($s) {
                    $query->where('title', 'like', "%{$s}%")
                        ->orWhere('release_number', 'like', "%{$s}%")
                        ->orWhere('content', 'like', "%{$s}%");
                });
            });

        $releases = $query->latest()->paginate(15);

        return view('admin.transparency.releases.index', [
            'releases' => $releases,
            'releaseTypes' => Release::RELEASE_TYPES,
            'statuses' => Release::STATUSES,
        ]);
    }

    /**
     * Show the form for creating a new release.
     */
    public function create()
    {
        return view('admin.transparency.releases.create', [
            'releaseTypes' => Release::RELEASE_TYPES,
            'nextReleaseNumber' => Release::generateReleaseNumber(),
        ]);
    }

    /**
     * Store a newly created release.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'release_type' => 'required|in:'.implode(',', array_keys(Release::RELEASE_TYPES)),
            'contact_person' => 'required|string|max:100',
            'contact_email' => 'required|email|max:100',
            'contact_phone' => 'nullable|string|max:50',
            'status' => 'required|in:'.implode(',', array_keys(Release::STATUSES)),
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif',
        ]);

        $validated['created_by'] = auth()->id();

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('releases', 'public');
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

        Release::create($validated);

        return redirect()
            ->route('admin.transparency.releases.index')
            ->with('success', 'Release created successfully.');
    }

    /**
     * Display the specified release.
     */
    public function show(Release $release)
    {
        $release->load('author');

        return view('admin.transparency.releases.show', [
            'release' => $release,
        ]);
    }

    /**
     * Show the form for editing the specified release.
     */
    public function edit(Release $release)
    {
        return view('admin.transparency.releases.edit', [
            'release' => $release,
            'releaseTypes' => Release::RELEASE_TYPES,
            'statuses' => Release::STATUSES,
        ]);
    }

    /**
     * Update the specified release.
     */
    public function update(Request $request, Release $release)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'release_type' => 'required|in:'.implode(',', array_keys(Release::RELEASE_TYPES)),
            'contact_person' => 'required|string|max:100',
            'contact_email' => 'required|email|max:100',
            'contact_phone' => 'nullable|string|max:50',
            'status' => 'required|in:'.implode(',', array_keys(Release::STATUSES)),
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif',
        ]);

        // Handle new file uploads
        if ($request->hasFile('attachments')) {
            $attachments = $release->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('releases', 'public');
                $attachments[] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ];
            }
            $validated['attachments'] = $attachments;
        }

        // Set published_at if publishing for the first time
        if ($validated['status'] === 'Published' && ! $release->published_at) {
            $validated['published_at'] = now();
        }

        $release->update($validated);

        return redirect()
            ->route('admin.transparency.releases.index')
            ->with('success', 'Release updated successfully.');
    }

    /**
     * Remove the specified release.
     */
    public function destroy(Release $release)
    {
        // Delete attachments
        if ($release->attachments) {
            foreach ($release->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $release->delete();

        return redirect()
            ->route('admin.transparency.releases.index')
            ->with('success', 'Release deleted successfully.');
    }

    /**
     * Remove an attachment from a release.
     */
    public function removeAttachment(Request $request, Release $release)
    {
        $request->validate(['index' => 'required|integer|min:0']);
        $index = (int) $request->input('index');
        $attachments = $release->attachments ?? [];

        if (isset($attachments[$index])) {
            Storage::disk('public')->delete($attachments[$index]['path']);
            unset($attachments[$index]);
            $release->update(['attachments' => array_values($attachments)]);
        }

        return back()->with('success', 'Attachment removed.');
    }

    /**
     * Toggle publish status.
     */
    public function togglePublish(Release $release)
    {
        if ($release->status === 'Published') {
            $release->update(['status' => 'Draft']);
            $message = 'Release unpublished.';
        } else {
            $release->update([
                'status' => 'Published',
                'published_at' => $release->published_at ?? now(),
            ]);
            $message = 'Release published.';
        }

        return back()->with('success', $message);
    }
}
