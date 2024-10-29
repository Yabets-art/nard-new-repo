@extends('admin.layout')

@section('content')
<div class="container">
    <h1>Manage Featured Products</h1>

    <div class="featured-products-header mb-4">
        <button class="btn btn-primary" data-toggle="modal" data-target="#productModal" onclick="addNewProduct()">
            + Add New Product
        </button>
    </div>

    <div class="row">
        @foreach($featuredProducts as $product)
            <div class="col-md-4 mb-4">
                <div class="product-item text-center border p-3 hover-zoom">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image" style="width: 100%; height: 200px; object-fit: cover;">
                    <h3 class="mt-2">{{ $product->name }}</h3>
                    <button class="btn btn-warning mr-2" onclick="editProduct({{ $product->id }})">Edit</button>
                    <form action="{{ route('admin.featured-products.destroy', $product->id) }}" method="POST" style="display:inline;" 
                          onsubmit="return confirm('Are you sure you want to delete this product?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal for adding/editing product -->
    <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="productModalLabel">Add New Product</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body bg-light">
                    <form action="{{ route('admin.featured-products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf
                        <input type="hidden" name="id" id="productId">

                        <div class="form-group">
                            <label for="productName" class="font-weight-bold">Product Name</label>
                            <input type="text" name="name" id="productName" class="form-control" placeholder="Enter product name">
                        </div>

                        <div class="form-group">
                            <label for="productImage" class="font-weight-bold">Upload Image</label>
                            <input type="file" name="image" id="productImage" class="form-control-file" accept="image/*">
                        </div>

                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Post Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    .hover-zoom {
        transition: transform 0.2s;
    }

    .hover-zoom:hover {
        transform: scale(0.98); /* Zoom out effect */
    }
</style>

<script>
    function addNewProduct() {
        $('#productModalLabel').text('Add New Product');
        $('#productForm').trigger('reset'); // Clear the form fields
        $('#productId').val(''); // Clear product ID
        $('#productModal').modal('show');
    }

    function editProduct(id) {
        $('#productModalLabel').text('Edit Product');
        $('#productId').val(id);

        // Fetch product data via AJAX
        $.ajax({
            url: '/admin/featured-products/' + id + '/edit',
            method: 'GET',
            success: function(data) {
                $('#productName').val(data.name);
                $('#productModal').modal('show');
            },
            error: function() {
                alert('Could not fetch product details. Please try again.');
            }
        });
    }


    function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        $.ajax({
            url: '/admin/featured-products/' + id,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}' // Include CSRF token for security
            },
            success: function(response) {
                location.reload(); // Reload the page to see changes
            },
            error: function() {
                alert('Could not delete the product. Please try again.');
            }
        });
    }
}

    $('#productForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        var id = $('#productId').val();
        var url = id ? '/admin/featured-products/' + id : '/admin/featured-products';

        $.ajax({
            url: url,
            method: id ? 'PUT' : 'POST',
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(response) {
                location.reload(); // Reload to see changes
            },
            error: function(xhr) {
                alert('An error occurred. Please try again.');
            }
        });
    });
</script>

@endsection
