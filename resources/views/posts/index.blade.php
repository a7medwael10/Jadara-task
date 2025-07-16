@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <h3>Create Post</h3>

            <div class="card">
                <div class="card-body">
                    <form id="create-post-form" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="body" class="form-label">Body</label>
                            <textarea name="body" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image (optional)</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Create</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <h3>Posts</h3>
            <div id="posts-list">
                @foreach($posts as $post)
                    <div class="card mb-3" id="post-{{ $post->id }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{{ $post->body }}</p>
                            @if($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="img-fluid mb-2 rounded">
                            @endif
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $post->id }}">Delete</button>
                        </div>
                    </div>
                @endforeach

                {{ $posts->links() }}
            </div>
        </div>
    </div>

    {{-- Toast Message --}}
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
        <div id="toast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toast-message"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Toast handler
        function showToast(message, success = true) {
            const toast = new bootstrap.Toast(document.getElementById('toast'));
            const toastEl = document.getElementById('toast');
            toastEl.classList.remove('text-bg-success', 'text-bg-danger');
            toastEl.classList.add(success ? 'text-bg-success' : 'text-bg-danger');
            document.getElementById('toast-message').textContent = message;
            toast.show();
        }

        // Create
        $('#create-post-form').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('posts.store') }}",
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    showToast(response.message);
                    setTimeout(() => location.reload(), 1000);
                },
                error: function(xhr) {
                    showToast('Error creating post', false);
                }
            });
        });

        // Delete
        $('.delete-btn').on('click', function() {
            let postId = $(this).data('id');
            if (confirm('Are you sure?')) {
                $.ajax({
                    url: `/posts/${postId}`,
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        showToast(response.message);
                        $(`#post-${postId}`).fadeOut(300, function() {
                            $(this).remove();
                        });
                    },
                    error: function() {
                        showToast('Error deleting post', false);
                    }
                });
            }
        });
    </script>
@endpush
