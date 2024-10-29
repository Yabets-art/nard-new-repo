@extends('admin.layout')

@section('content')
<div class="container">
    <h1>Manage Promotion Banner</h1>

    <div class="promotion-banner-header mb-4">
        <button class="btn btn-warning" data-toggle="modal" data-target="#addPromotionModal">
            + Add New Promotion
        </button>
    </div>

    <div class="promotion-banner-list">
        @foreach($promotions as $promotion)
            <div class="promotion-item">
                @if(Str::contains($promotion->media, ['.jpg', '.jpeg', '.png']))
                    <img src="{{ asset('storage/' . $promotion->media) }}" alt="{{ $promotion->title }}" class="promotion-image">
                @elseif(Str::contains($promotion->media, ['.mp4', '.mov', '.avi']))
                    <video controls class="promotion-video">
                        <source src="{{ asset('storage/' . $promotion->media) }}" type="video/{{ pathinfo($promotion->media, PATHINFO_EXTENSION) }}">
                        Your browser does not support the video tag.
                    </video>
                @endif
                <div class="promotion-details">
                    <h3>{{ $promotion->title }}</h3>
                    <p>{{ $promotion->description }}</p>
                    <label>Status: 
                        <input type="checkbox" {{ $promotion->is_selected ? 'checked' : '' }} onclick="toggleStatus({{ $promotion->id }})">
                        Selected
                    </label>
                </div>
                <button class="btn btn-danger" onclick="deletePromotion({{ $promotion->id }})">Delete</button>
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
        fetch(`/admin/promotions/${id}/toggle`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ /* data to update status */ })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Status updated successfully!');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function deletePromotion(id) {
        if (confirm('Are you sure you want to delete this promotion?')) {
            fetch(`/admin/promotions/${id}`, {
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
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>

@endsection

