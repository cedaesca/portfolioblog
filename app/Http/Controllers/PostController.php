<?php

namespace App\Http\Controllers;

use App\Enums\PostType;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Interfaces\Services\PostServiceInterface;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PostController extends Controller
{
    public function __construct(private PostServiceInterface $postService)
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $articles = $this->postService->getAllPublishedArticles();
        $projects = $this->postService->getAllPublishedProjects();

        return view('posts.index')
            ->with([
                'articles' => $articles,
                'projects' => $projects
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('posts.create')->with(['postTypes' => PostType::cases()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $post = $this->postService->storePost($validated);
        } catch (Exception $e) {
            return back()->withErrors('An error occured while creating the post. Please try again.');
        }

        return redirect()->to(route('posts.show', $post->slug));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug): View
    {
        $post = $this->postService->getPublishedPostBySlug($slug);

        abort_if(!$post, 404);

        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        $post = $this->postService->getPublishedPostBySlug($slug);

        abort_if(!$post, 404);

        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $slug)
    {
        $validated = $request->validated();

        try {
            $wasUpdated = $this->postService->updatePost($slug, $validated);

            if (!$wasUpdated) {
                return back()->withErrors(['general' => 'No changes were made to the post. Please try again.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['general' => 'An error occured while updating the post. Please try again.']);
        }

        return redirect()->to(route('posts.show', $validated['slug'] ?? $slug));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
