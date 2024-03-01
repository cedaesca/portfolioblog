@extends('layouts.app')

@section('title', 'Edit Post | ' . config('app.name'))

@section('content')
<div class="container">
    <h1>Edit Post</h1>

    @if($errors->has('general'))
        <div style="color: red;">{{ $errors->first('general') }}</div>
    @endif

    <form id="update-form" action="{{ route('posts.update', $post->slug) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="slug">Slug</label>
            <br>
            <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $post->slug) }}" readonly>
        </div>
        <br>

        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="disableSlugProtection" name="disable_slug_protection">
            <label class="form-check-label" for="disableSlugProtection">Disable slug update protection</label>
        </div>
        <br>

        <div>
            <label for="title">Title</label>
            <br>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $post->title) }}" required>
            @if ($errors->has('title'))
                <div style="color: red;">{{ $errors->first('title') }}</div>
            @endif
        </div>
        <br>

        <div>
            <label for="content">Content</label>
            <br>
            <textarea class="form-control" id="content" name="content" rows="4" required>{{ old('content', $post->content) }}</textarea>
            @if ($errors->has('content'))
                <div style="color: red;">{{ $errors->first('content') }}</div>
            @endif
        </div>
        <br>

        <div">
            <label for="type">Type</label>
            <br>
            <select class="form-control" id="type" name="type" required>
                <option value="article" @if(old('type', $post->type->value) == 'article') selected @endif>Article</option>
                <option value="project" @if(old('type', $post->type->value) == 'project') selected @endif>Project</option>
            </select>
            @if ($errors->has('type'))
                <div style="color: red;">{{ $errors->first('type') }}</div>
            @endif
        </div>
        <br>

        <div>
            <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" @if(old('is_published', $post->is_published)) checked @endif>
            <label class="form-check-label" for="is_published">
                Publish
            </label>
            @if ($errors->has('is_published'))
                <div style="color: red;">{{ $errors->first('is_published') }}</div>
            @endif
        </div>
        <br>

        <button type="submit" class="btn btn-primary">Update Post</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        const disableSlugProtectionCheckbox = document.getElementById('disableSlugProtection');
        let originalSlug = slugInput.value;
        let suggestedSlug = '';

        const generateSlug = (title) => title.toLowerCase().replace(/[\s\W-]+/g, '-').replace(/^-+|-+$/g, '');

        disableSlugProtectionCheckbox.addEventListener('change', () => {
            if (disableSlugProtectionCheckbox.checked) {
                const confirmDisable = confirm("Disabling slug update protection may have SEO implications and require redirects. Are you sure?");
                if (confirmDisable) {
                    slugInput.removeAttribute('readonly');
                    suggestedSlug = generateSlug(titleInput.value);
                    slugInput.value = suggestedSlug;
                } else {
                    disableSlugProtectionCheckbox.checked = false;
                }
            } else {
                slugInput.setAttribute('readonly', true);
                slugInput.value = originalSlug;
            }
        });

        titleInput.addEventListener('input', () => {
            if (disableSlugProtectionCheckbox.checked) {
                suggestedSlug = generateSlug(titleInput.value);
                slugInput.value = suggestedSlug;
            }
        });

        document.getElementById('update-form').addEventListener('submit', (e) => {
            if (slugInput.value !== suggestedSlug && slugInput.value !== originalSlug) {
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

