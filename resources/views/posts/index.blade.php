@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
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
@endsection
