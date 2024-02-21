@extends('layouts.app')

@section('title', $post->title . ' | ' . config('app.name'))

@section('content')
    <article>
        <header>
            <h1>{{ $post->title }}</h1>
            <p>Published on: <time datetime="{{ $post->created_at->toDateString() }}">{{ $post->created_at->format('F d, Y') }}</time></p>
        </header>
        
        <section>
            {{ $post->content }}
        </section>

        <footer>
            <p>Post type: <strong>{{ $post->type->value }}</strong></p>
        </footer>
    </article>

    <nav>
        <a href="{{ route('index') }}">Go Back</a>
    </nav>
@endsection
