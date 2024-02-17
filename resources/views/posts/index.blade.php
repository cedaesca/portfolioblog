<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ config('app.name') }}</title>
    </head>
    <body>
        <h1>Projects</h1>
        <ul>
            @foreach($projects as $project)
                <li>{{ $project->title }}</li>
            @endforeach
        </ul>
        <br />
        <h1>Articles</h1>
        <ul>
            @foreach($articles as $article)
                <li>{{ $article->title }}</li>
            @endforeach
        </ul>
    </body>
</html>
