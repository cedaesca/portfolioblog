@extends('layouts.app')

@section('title', 'Create Post | ' . config('app.name'))

@section('content')
    <h1>Create a New Post</h1>
    <form action="{{ route('posts.store') }}" method="POST">
        @csrf
        
        <div>
            <label for="slug">Slug:</label>
            <br>
            <input type="text" id="slug" name="slug" value="{{ old('slug') }}" required>
            @if ($errors->has('slug'))
                <div style="color: red;">{{ $errors->first('slug') }}</div>
            @endif
        </div>
        <br>

        <div>
            <label for="title">Title:</label>
            <br>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required>
            @if ($errors->has('title'))
                <div style="color: red;">{{ $errors->first('title') }}</div>
            @endif
        </div>
        <br>
        
        <div>
            <label for="content">Content:</label>
            <br>
            <textarea id="content" name="content" rows="4" required>{{ old('content') }}</textarea>
            @if ($errors->has('content'))
                <div style="color: red;">{{ $errors->first('content') }}</div>
            @endif
        </div>
        <br>
        
        <div>
            <label for="type">Type:</label>
            <br>
            <select id="type" name="type" required>
                @foreach($postTypes as $postType)
                    <option value="{{ $postType->value }}" @if (old('type') == $postType->value) selected @endif>
                        {{ ucfirst($postType->name) }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('type'))
                <div style="color: red;">{{ $errors->first('type') }}</div>
            @endif
        </div>
        <br>
        
        <div>
            <label for="is_published">Publish:</label>
            <input type="checkbox" id="is_published" name="is_published" value="1" @if (old('is_published')) checked @endif>
        </div>
        <br>
        
        <button type="submit">Create Post</button>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');
            let suggestedSlug = '';

            const generateSlug = (title) => title
                .toLowerCase()
                .replace(/[\s\W-]+/g, '-')
                .replace(/^-+|-+$/g, '');

            titleInput.addEventListener('input', () => {
                suggestedSlug = generateSlug(titleInput.value);
                slugInput.value = suggestedSlug;
            });

            document.querySelector('form').addEventListener('submit', (e) => {
                if (slugInput.value !== suggestedSlug) {
                    const confirmSlugChange = confirm('The slug has been manually modified. Do you want to keep the changes and continue?');

                    if (!confirmSlugChange) {
                        e.preventDefault();
                        slugInput.value = suggestedSlug;
                    }
                }
            });
        });
    </script>
@endpush
