@extends('admin.layout')

@section('title', 'Product Management')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Products</h1>
    <p>View and manage the products in the store.</p>

    <!-- Button to trigger the modal for adding a product -->
    <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#addProductModal">
        <i class="fas fa-plus-circle"></i> Add New Product
    </button>

    <!-- Modal for adding a new product -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addProductModalLabel">
                        <i class="fas fa-box"></i> Add New Product
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body bg-light">
                    <form method="POST" action="{{ route('admin.product.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name" class="font-weight-bold">Product Name</label>
                                <input type="text" class="form-control" id="name" name="name" required placeholder="Enter product name">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="price" class="font-weight-bold">Price</label>
                                <input type="number" class="form-control" id="price" name="price" required placeholder="Enter product price">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="font-weight-bold">Product Description</label>
                            <textarea class="form-control" id="description" name="description" required placeholder="Enter product description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image" class="font-weight-bold">Product Image</label>
                            <input type="file" class="form-control-file" id="image" name="image" required>
                        </div>
                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Add Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Table of existing products -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $index => $product)
                    <tr>
                        <td>{{ $products->firstItem() + $index }}</td>
                        <td>{{ $product->name }}</td>
                        <td>${{ $product->price }}</td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editProductModal-{{ $product->id }}">Edit</button>

                            <div class="modal fade" id="editProductModal-{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel-{{ $product->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <div class="modal-content shadow-lg">
                                        <div class="modal-header bg-warning text-white">
                                            <h5 class="modal-title" id="editProductModalLabel-{{ $product->id }}">
                                                <i class="fas fa-edit"></i> Edit Product: {{ $product->name }}
                                            </h5>
                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body bg-light">
                                            <form method="POST" action="{{ route('admin.product.update', $product->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('POST')
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="name-{{ $product->id }}" class="font-weight-bold">Product Name</label>
                                                        <input type="text" class="form-control" id="name-{{ $product->id }}" name="name" value="{{ $product->name }}" required>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="price-{{ $product->id }}" class="font-weight-bold">Price</label>
                                                        <input type="number" class="form-control" id="price-{{ $product->id }}" name="price" value="{{ $product->price }}" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="description-{{ $product->id }}" class="font-weight-bold">Product Description</label>
                                                    <textarea class="form-control" id="description-{{ $product->id }}" name="description" required>{{ $product->description }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="image-{{ $product->id }}" class="font-weight-bold">Product Image</label>
                                                    <input type="file" class="form-control-file" id="image-{{ $product->id }}" name="image">
                                                    <small class="form-text text-muted">Leave blank to keep the current image.</small>
                                                </div>
                                                <div class="modal-footer mt-4">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Update Product Info</button>
                                                </div>
                                            </form>
                                            <form action="{{ route('admin.product.delete', $product->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        <ul class="pagination">
            <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $products->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            @for ($i = 1; $i <= $products->lastPage(); $i++)
                <li class="page-item {{ $products->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            <li class="page-item {{ $products->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $products->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </div>
@endsection
