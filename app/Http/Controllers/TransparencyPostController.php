<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransparencyPostRequest;
use App\Http\Requests\UpdateTransparencyPostRequest;
use App\Models\Campus;
use App\Models\TransparencyPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TransparencyPostController extends Controller
{
    /**
     * Display a listing of the resource (public).
     */
    public function index(Request $request)
    {
        $query = TransparencyPost::with(['author', 'campus'])
            ->published();

        // If not logged in, only show public posts
        if (! auth()->check()) {
            $query->public();
        }

        // Apply filters
        if ($request->type) {
            $query->ofType($request->type);
        }

        if ($request->campus_id) {
            $query->forCampus($request->campus_id);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('content', 'like', "%{$request->search}%");
            });
        }

        $posts = $query->latest('published_at')->paginate(12);
        $types = TransparencyPost::TYPES;
        $campuses = Campus::active()->get();

        return view('transparency.index', compact('posts', 'types', 'campuses'));
    }

    /**
     * Display the specified resource (public).
     */
    public function show(TransparencyPost $transparencyPost)
    {
        $this->authorize('view', $transparencyPost);

        $transparencyPost->load(['author', 'campus']);

        // Get related posts
        $relatedPosts = TransparencyPost::where('id', '!=', $transparencyPost->id)
            ->where('type', $transparencyPost->type)
            ->published()
            ->public()
            ->latest()
            ->take(3)
            ->get();

        return view('transparency.show', compact('transparencyPost', 'relatedPosts'));
    }

    /**
     * Display admin listing.
     */
    public function adminIndex(Request $request)
    {
        $this->authorize('create', TransparencyPost::class);

        $posts = TransparencyPost::with(['author', 'campus'])
            ->when($request->type, fn ($q, $t) => $q->ofType($t))
            ->when($request->search, function ($q, $s) {
                $q->where('title', 'like', "%{$s}%");
            })
            ->latest()
            ->paginate(15);

        $types = TransparencyPost::TYPES;

        return view('admin.transparency.index', compact('posts', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', TransparencyPost::class);

        $types = TransparencyPost::TYPES;
        $campuses = Campus::active()->get();

        return view('admin.transparency.create', compact('types', 'campuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransparencyPostRequest $request)
    {
        $this->authorize('create', TransparencyPost::class);

        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        // Set published_at if publishing
        if ($data['is_published'] ?? false) {
            $data['published_at'] = $data['published_at'] ?? now();
        }

        $post = TransparencyPost::create($data);

        return redirect()
            ->route('admin.transparency.index')
            ->with('success', 'Post created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransparencyPost $transparencyPost)
    {
        $this->authorize('update', $transparencyPost);

        $types = TransparencyPost::TYPES;
        $campuses = Campus::active()->get();

        return view('admin.transparency.edit', compact('transparencyPost', 'types', 'campuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransparencyPostRequest $request, TransparencyPost $transparencyPost)
    {
        $this->authorize('update', $transparencyPost);

        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($transparencyPost->featured_image) {
                Storage::disk('public')->delete($transparencyPost->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        // Set published_at if publishing for the first time
        if (($data['is_published'] ?? false) && ! $transparencyPost->published_at) {
            $data['published_at'] = $data['published_at'] ?? now();
        }

        $transparencyPost->update($data);

        return redirect()
            ->route('admin.transparency.index')
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransparencyPost $transparencyPost)
    {
        $this->authorize('delete', $transparencyPost);

        // Delete featured image
        if ($transparencyPost->featured_image) {
            Storage::disk('public')->delete($transparencyPost->featured_image);
        }

        $transparencyPost->delete();

        return redirect()
            ->route('admin.transparency.index')
            ->with('success', 'Post deleted successfully.');
    }

    /**
     * Toggle publish status.
     */
    public function togglePublish(TransparencyPost $transparencyPost)
    {
        $this->authorize('publish', $transparencyPost);

        $transparencyPost->update([
            'is_published' => ! $transparencyPost->is_published,
            'published_at' => ! $transparencyPost->is_published ? now() : $transparencyPost->published_at,
        ]);

        $status = $transparencyPost->is_published ? 'published' : 'unpublished';

        return redirect()
            ->back()
            ->with('success', "Post {$status} successfully.");
    }
}
