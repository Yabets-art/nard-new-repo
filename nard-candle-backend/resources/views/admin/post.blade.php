@extends('admin.layout')

@section('title', 'Post Management')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Post Management</h1>
    <p>Create and manage blog posts or announcements.</p>

    <!-- Button to trigger modal for adding a new post -->
    <button class="btn btn-success mb-4" data-toggle="modal" data-target="#postModal">
        + Add Post
    </button>

    <!-- List of existing posts as a table -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>#</th> <!-- Changed from ID to # -->
                    <th>Title</th>
                    <th>Description</th>
                    <th>Media</th>
                    <th>Author</th>
                    <th>Link</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $index => $post) <!-- Added $index -->
                    <tr>
                        <td>{{ $index + $posts->firstItem() }}</td> <!-- Incremental index -->
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->short_description }}</td>
                        <td>
                            @if($post->media)
                                <img src="{{ asset($post->media) }}" alt="Post Media" class="img-thumbnail" width="100">
                            @else
                                <span>No Image</span>
                            @endif
                        </td>
                        <td>{{ $post->author ?? 'Admin' }}</td>
                        <td>
                            <a href="{{ $post->link }}" target="_blank" class="btn btn-link">View Post</a>
                        </td>
                        <td>
                            <a href="{{ route('admin.post.edit', $post->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.post.destroy', $post->id) }}" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        <ul class="pagination">
            <li class="page-item {{ $posts->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $posts->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span> <!-- Left Arrow -->
                </a>
            </li>

            @for ($i = 1; $i <= $posts->lastPage(); $i++)
                <li class="page-item {{ $posts->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $posts->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            <li class="page-item {{ $posts->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $posts->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span> <!-- Right Arrow -->
                </a>
            </li>
        </ul>
    </div>

    <!-- Bootstrap Modal for Adding Post -->
    <div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="postModalLabel">Add Post</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body bg-light">
                    <form action="{{ route('admin.post.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
                        @csrf
                        <input type="hidden" name="id" id="postId">

                        <div class="form-group">
                            <label for="postTitle" class="font-weight-bold">Title</label>
                            <input type="text" name="title" id="postTitle" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="postDescription" class="font-weight-bold">Description</label>
                            <input type="text" name="short_description" id="postDescription" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="postLink" class="font-weight-bold">Link</label>
                            <input type="url" name="link" id="postLink" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="postAuthor" class="font-weight-bold">Author</label>
                            <input type="text" name="author" id="postAuthor" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="postMedia" class="font-weight-bold">Media (optional)</label>
                            <input type="file" name="media" id="postMedia" class="form-control-file">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Post</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
