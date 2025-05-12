@extends('admin.layout')

@section('content')
<div class="container">
    <h1 class="mb-4">Manage Promotion Banner</h1>

    <div class="promotion-banner-header mb-4">
        <button class="btn btn-warning" data-toggle="modal" data-target="#addPromotionModal">
            + Add New Promotion
        </button>
    </div>

    <div class="row">
        @foreach($promotions as $promotion)
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    @if(Str::endsWith($promotion->media, ['.jpg', '.jpeg', '.png']))
                        <img src="{{ asset('storage/' . $promotion->media) }}" alt="{{ $promotion->title }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    @elseif(Str::endsWith($promotion->media, ['.mp4', '.mov', '.avi']))
                        <video controls class="card-img-top" style="height: 200px; object-fit: cover;">
                            <source src="{{ asset('storage/' . $promotion->media) }}" type="video/{{ pathinfo($promotion->media, PATHINFO_EXTENSION) }}">
                            Your browser does not support the video tag.
                        </video>
                    @else
                        <img src="{{ asset('images/default-placeholder.png') }}" class="card-img-top" alt="No media">
                    @endif

                    <div class="card-body">
                        <h5 class="card-title">{{ $promotion->title }}</h5>
                        <p class="card-text">{{ $promotion->description }}</p>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="promotionCheck{{ $promotion->id }}" {{ $promotion->is_selected ? 'checked' : '' }} onclick="toggleStatus({{ $promotion->id }})">
                            <label class="form-check-label" for="promotionCheck{{ $promotion->id }}">Selected</label>
                        </div>
                        <button class="btn btn-danger mt-3" onclick="deletePromotion({{ $promotion->id }})">Delete</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal for adding new promotion -->
    <div class="modal fade" id="addPromotionModal" tabindex="-1" role="dialog" aria-labelledby="addPromotionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addPromotionModalLabel">
                        <i class="fas fa-bullhorn"></i> Add New Promotion
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body bg-light">
                    <form action="{{ route('admin.promotions.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="promotionTitle" class="font-weight-bold">Title</label>
                            <input type="text" name="title" id="promotionTitle" class="form-control" required placeholder="Enter promotion title">
                        </div>
                        <div class="form-group">
                            <label for="promotionDescription" class="font-weight-bold">Description</label>
                            <textarea name="description" id="promotionDescription" class="form-control" required placeholder="Enter promotion description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="promotionImage" class="font-weight-bold">Upload Image or Video</label>
                            <input type="file" name="media" id="promotionImage" class="form-control-file" accept="image/*,video/*" required>
                        </div>
                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Post Promotion</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleStatus(id) {
        fetch(`{{ url('admin/promotions') }}/${id}/toggle`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Status updated successfully!');
            } else {
                alert('Failed to update status.');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function deletePromotion(id) {
        if (confirm('Are you sure you want to delete this promotion?')) {
            fetch(`{{ url('admin/promotions') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                    alert('Promotion deleted successfully!');
                } else {
                    alert('Failed to delete promotion.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>
@endsection
