<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CollectionController extends Controller
{
    /**
     * Display a listing of collections.
     */
    public function index(Request $request)
    {
        $query = Collection::with('author')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->report_type, fn ($q, $t) => $q->where('report_type', $t))
            ->when($request->search, function ($q, $s) {
                $q->where(function ($query) use ($s) {
                    $query->where('title', 'like', "%{$s}%")
                        ->orWhere('report_number', 'like', "%{$s}%")
                        ->orWhere('period_covered', 'like', "%{$s}%");
                });
            });

        $collections = $query->latest()->paginate(15);

        return view('admin.transparency.collections.index', [
            'collections' => $collections,
            'reportTypes' => Collection::REPORT_TYPES,
            'statuses' => Collection::STATUSES,
        ]);
    }

    /**
     * Show the form for creating a new collection.
     */
    public function create()
    {
        return view('admin.transparency.collections.create', [
            'reportTypes' => Collection::REPORT_TYPES,
        ]);
    }

    /**
     * Store a newly created collection.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'period_covered' => 'required|string|max:100',
            'total_collected' => 'required|numeric|min:0',
            'report_type' => 'required|in:'.implode(',', array_keys(Collection::REPORT_TYPES)),
            'breakdown_data' => 'nullable|json',
            'status' => 'required|in:'.implode(',', array_keys(Collection::STATUSES)),
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['report_number'] = Collection::generateReportNumber(
            $validated['report_type'],
            $validated['period_covered']
        );

        // Parse breakdown data
        if (! empty($validated['breakdown_data'])) {
            $validated['breakdown_data'] = json_decode($validated['breakdown_data'], true);
        }

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('collections', 'public');
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

        Collection::create($validated);

        return redirect()
            ->route('admin.transparency.collections.index')
            ->with('success', 'Collection report created successfully.');
    }

    /**
     * Display the specified collection.
     */
    public function show(Collection $collection)
    {
        $collection->load('author');

        return view('admin.transparency.collections.show', [
            'collection' => $collection,
        ]);
    }

    /**
     * Show the form for editing the specified collection.
     */
    public function edit(Collection $collection)
    {
        return view('admin.transparency.collections.edit', [
            'collection' => $collection,
            'reportTypes' => Collection::REPORT_TYPES,
            'statuses' => Collection::STATUSES,
        ]);
    }

    /**
     * Update the specified collection.
     */
    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'period_covered' => 'required|string|max:100',
            'total_collected' => 'required|numeric|min:0',
            'report_type' => 'required|in:'.implode(',', array_keys(Collection::REPORT_TYPES)),
            'breakdown_data' => 'nullable|json',
            'status' => 'required|in:'.implode(',', array_keys(Collection::STATUSES)),
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif',
        ]);

        // Parse breakdown data
        if (! empty($validated['breakdown_data'])) {
            $validated['breakdown_data'] = json_decode($validated['breakdown_data'], true);
        }

        // Handle new file uploads
        if ($request->hasFile('attachments')) {
            $attachments = $collection->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('collections', 'public');
                $attachments[] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ];
            }
            $validated['attachments'] = $attachments;
        }

        // Set published_at if publishing for the first time
        if ($validated['status'] === 'Published' && ! $collection->published_at) {
            $validated['published_at'] = now();
        }

        $collection->update($validated);

        return redirect()
            ->route('admin.transparency.collections.index')
            ->with('success', 'Collection report updated successfully.');
    }

    /**
     * Remove the specified collection.
     */
    public function destroy(Collection $collection)
    {
        // Delete attachments
        if ($collection->attachments) {
            foreach ($collection->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $collection->delete();

        return redirect()
            ->route('admin.transparency.collections.index')
            ->with('success', 'Collection report deleted successfully.');
    }

    /**
     * Remove an attachment from a collection.
     */
    public function removeAttachment(Request $request, Collection $collection)
    {
        $request->validate(['index' => 'required|integer|min:0']);
        $index = (int) $request->input('index');
        $attachments = $collection->attachments ?? [];

        if (isset($attachments[$index])) {
            Storage::disk('public')->delete($attachments[$index]['path']);
            unset($attachments[$index]);
            $collection->update(['attachments' => array_values($attachments)]);
        }

        return back()->with('success', 'Attachment removed.');
    }

    /**
     * Toggle publish status.
     */
    public function togglePublish(Collection $collection)
    {
        if ($collection->status === 'Published') {
            $collection->update(['status' => 'Draft']);
            $message = 'Collection report unpublished.';
        } else {
            $collection->update([
                'status' => 'Published',
                'published_at' => $collection->published_at ?? now(),
            ]);
            $message = 'Collection report published.';
        }

        return back()->with('success', $message);
    }
}
