@extends('admin.layout') 

@section('content')
<div class="container">
    <h1>Manage YouTube Videos</h1>

    <div class="youtube-header mb-4">
        <button class="btn btn-primary" data-toggle="modal" data-target="#videoModal">
            + Add New Video
        </button>
    </div>

    <div class="youtube-list">
        @foreach($videos as $video)
            <div class="video-item mb-3">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ $video->link }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <div class="video-details">
                    <h3 style="font-size: smaller;">{{ $video->description }}</h3>
                    <button class="btn btn-warning mr-2" onclick="editVideo({{ $video->id }})">Edit</button>
                    <button class="btn btn-danger" onclick="deleteVideo({{ $video->id }})">Delete</button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal for adding/editing video -->
    <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="videoModalLabel">Add New Video</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body bg-light">
                    <form action="{{ route('admin.youtube-videos.store') }}" method="POST" id="videoForm">
                        @csrf
                        <input type="hidden" name="id" id="videoId">

                        <div class="form-group">
                            <label for="videoLink" class="font-weight-bold">YouTube Video Link</label>
                            <input type="text" name="link" id="videoLink" class="form-control" required placeholder="Enter YouTube video link">
                        </div>

                        <div class="form-group">
                            <label for="videoDescription" class="font-weight-bold">Description</label>
                            <textarea name="description" id="videoDescription" class="form-control" required placeholder="Enter video description"></textarea>
                        </div>

                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Post Video</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function addNewVideo() {
    $('#videoModalLabel').text('Add New Video');
    $('#videoForm').attr('action', '{{ route("admin.youtube-videos.store") }}'); // Set form action to store route
    $('#videoForm').trigger('reset'); // Reset the form fields
    $('#videoModal').modal('show');
}

function closeModal() {
    $('#videoModal').modal('hide');
}

function editVideo(id) {
    $.ajax({
        url: `/admin/youtube-videos/${id}`,  // Correct URL pattern
        method: 'GET',  // Ensure GET request is sent
        success: function(video) {
            $('#videoModalLabel').text('Edit Video');
            $('#videoForm').attr('action', `/admin/youtube-videos/${id}`);  // Correct form action
            $('#videoLink').val(video.link);  // Set the YouTube video link
            $('#videoDescription').val(video.description);  // Set the description
            $('#videoModal').modal('show');  // Show the modal
        },
        error: function(error) {
            console.error('Error fetching video data:', error);  // Add error handling
        }
    });
}



function deleteVideo(id) {
    if (confirm('Are you sure you want to delete this video?')) {
        $.ajax({
            url: `/admin/youtube-videos/${id}`,
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function(response) {
                window.location.reload(); // Reload page after deletion
            }
        });
    }
}
</script>

@endsection
