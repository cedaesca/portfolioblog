<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post | {{ config('app.name') }}</title>
</head>
<body>
    <main>
        <h1>Create a New Post</h1>
        <form action="{{ route('posts.store') }}" method="POST">
            @csrf
            
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
    </main>
</body>
</html>
