<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
</head>
<body>
    <main>
        <section>
            <h1>Projects</h1>
            <ul>
                @foreach($projects as $project)
                    <li><a href="{{ route('posts.show', $project->slug) }}">{{ $project->title }}</a></li>
                @endforeach
            </ul>
        </section>
        
        <section>
            <h1>Articles</h1>
            <ul>
                @foreach($articles as $article)
                    <li><a href="{{ route('posts.show', $article->slug) }}">{{ $article->title }}</a></li>
                @endforeach
            </ul>
        </section>
    </main>
</body>
</html>
