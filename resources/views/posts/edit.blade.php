@extends('layouts.app')

@section('title', 'Edit Post | ' . config('app.name'))

@section('content')
    <div class="container">
        <h1>Edit Post</h1>
        <form action="{{ route('posts.update', $post->slug) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Title</label>
                <br>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $post->title) }}" required>
            </div>
            <br>

            <div class="form-group">
                <label for="content">Content</label>
                <br>
                <textarea class="form-control" id="content" name="content" rows="4" required>{{ old('content', $post->content) }}</textarea>
            </div>
            <br>

            <div class="form-group">
                <label for="type">Type</label>
                <br>
                <select class="form-control" id="type" name="type" required>
                    <option value="article" @if(old('type', $post->type->value) == 'article') selected @endif>Article</option>
                    <option value="project" @if(old('type', $post->type->value) == 'project') selected @endif>Project</option>
                </select>
            </div>
            <br>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" @if(old('is_published', $post->is_published)) checked @endif>
                <label class="form-check-label" for="is_published">
                    Publish
                </label>
            </div>
            <br>

            <button type="submit" class="btn btn-primary">Update Post</button>
        </form>
    </div>
@endsection
