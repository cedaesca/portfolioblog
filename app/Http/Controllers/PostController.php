<?php

namespace App\Http\Controllers;

use App\Enums\PostType;
use App\Interfaces\Services\PostServiceInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(private PostServiceInterface $postService) {}

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
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
