<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Memo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemoController extends Controller
{
    /**
     * Display a listing of memos.
     */
    public function index(Request $request)
    {
        $query = Memo::with('author')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->category, fn ($q, $c) => $q->where('category', $c))
            ->when($request->priority, fn ($q, $p) => $q->where('priority', $p))
            ->when($request->search, function ($q, $s) {
                $q->where(function ($query) use ($s) {
                    $query->where('title', 'like', "%{$s}%")
                        ->orWhere('memo_number', 'like', "%{$s}%")
                        ->orWhere('content', 'like', "%{$s}%");
                });
            });

        $memos = $query->latest()->paginate(15);

        return view('admin.transparency.memos.index', [
            'memos' => $memos,
            'categories' => Memo::CATEGORIES,
            'priorities' => Memo::PRIORITIES,
            'statuses' => Memo::STATUSES,
        ]);
    }

    /**
     * Show the form for creating a new memo.
     */
    public function create()
    {
        return view('admin.transparency.memos.create', [
            'categories' => Memo::CATEGORIES,
            'issuedBy' => Memo::ISSUED_BY,
            'priorities' => Memo::PRIORITIES,
            'nextMemoNumber' => Memo::generateMemoNumber(),
        ]);
    }

    /**
     * Store a newly created memo.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'category' => 'required|in:'.implode(',', array_keys(Memo::CATEGORIES)),
            'issued_by' => 'required|in:'.implode(',', array_keys(Memo::ISSUED_BY)),
            'effective_date' => 'required|date',
            'expiration_date' => 'nullable|date|after:effective_date',
            'requires_acknowledgment' => 'boolean',
            'priority' => 'required|in:'.implode(',', array_keys(Memo::PRIORITIES)),
            'status' => 'required|in:'.implode(',', array_keys(Memo::STATUSES)),
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif', // 10MB max per file
        ]);

        $validated['created_by'] = auth()->id();
        $validated['requires_acknowledgment'] = $request->boolean('requires_acknowledgment');

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('memos', 'public');
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

        Memo::create($validated);

        return redirect()
            ->route('admin.transparency.memos.index')
            ->with('success', 'Memo created successfully.');
    }

    /**
     * Display the specified memo.
     */
    public function show(Memo $memo)
    {
        $memo->load(['author', 'acknowledgedBy']);

        return view('admin.transparency.memos.show', [
            'memo' => $memo,
        ]);
    }

    /**
     * Show the form for editing the specified memo.
     */
    public function edit(Memo $memo)
    {
        return view('admin.transparency.memos.edit', [
            'memo' => $memo,
            'categories' => Memo::CATEGORIES,
            'issuedBy' => Memo::ISSUED_BY,
            'priorities' => Memo::PRIORITIES,
            'statuses' => Memo::STATUSES,
        ]);
    }

    /**
     * Update the specified memo.
     */
    public function update(Request $request, Memo $memo)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'category' => 'required|in:'.implode(',', array_keys(Memo::CATEGORIES)),
            'issued_by' => 'required|in:'.implode(',', array_keys(Memo::ISSUED_BY)),
            'effective_date' => 'required|date',
            'expiration_date' => 'nullable|date|after:effective_date',
            'requires_acknowledgment' => 'boolean',
            'priority' => 'required|in:'.implode(',', array_keys(Memo::PRIORITIES)),
            'status' => 'required|in:'.implode(',', array_keys(Memo::STATUSES)),
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif',
        ]);

        $validated['requires_acknowledgment'] = $request->boolean('requires_acknowledgment');

        // Handle new file uploads
        if ($request->hasFile('attachments')) {
            $attachments = $memo->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('memos', 'public');
                $attachments[] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ];
            }
            $validated['attachments'] = $attachments;
        }

        // Set published_at if publishing for the first time
        if ($validated['status'] === 'Published' && ! $memo->published_at) {
            $validated['published_at'] = now();
        }

        $memo->update($validated);

        return redirect()
            ->route('admin.transparency.memos.index')
            ->with('success', 'Memo updated successfully.');
    }

    /**
     * Remove the specified memo.
     */
    public function destroy(Memo $memo)
    {
        // Delete attachments
        if ($memo->attachments) {
            foreach ($memo->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $memo->delete();

        return redirect()
            ->route('admin.transparency.memos.index')
            ->with('success', 'Memo deleted successfully.');
    }

    /**
     * Remove an attachment from a memo.
     */
    public function removeAttachment(Request $request, Memo $memo)
    {
        $request->validate(['index' => 'required|integer|min:0']);
        $index = (int) $request->input('index');
        $attachments = $memo->attachments ?? [];

        if (isset($attachments[$index])) {
            Storage::disk('public')->delete($attachments[$index]['path']);
            unset($attachments[$index]);
            $memo->update(['attachments' => array_values($attachments)]);
        }

        return back()->with('success', 'Attachment removed.');
    }

    /**
     * Toggle publish status.
     */
    public function togglePublish(Memo $memo)
    {
        if ($memo->status === 'Published') {
            $memo->update(['status' => 'Draft']);
            $message = 'Memo unpublished.';
        } else {
            $memo->update([
                'status' => 'Published',
                'published_at' => $memo->published_at ?? now(),
            ]);
            $message = 'Memo published.';
        }

        return back()->with('success', $message);
    }

    /**
     * Preview memo.
     */
    public function preview(Memo $memo)
    {
        return view('admin.transparency.memos.preview', [
            'memo' => $memo,
        ]);
    }
}
