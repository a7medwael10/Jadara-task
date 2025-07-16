@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3>Edit Post</h3>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" value="{{ old('title', $post->title) }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="body" class="form-label">Body</label>
                            <textarea name="body" rows="4" class="form-control" required>{{ old('body', $post->body) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Replace Image (optional)</label>
                            <input type="file" name="image" class="form-control">
                            @if($post->image)
                                <div class="mt-2">
                                    <p>Current Image:</p>
                                    <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="img-thumbnail" width="200">
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Back</a>
                            <button type="submit" class="btn btn-primary">Update Post</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
