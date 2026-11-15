@extends('user.main')
@section('title', 'Add Product - ' . config('app.name'))

@push('css')
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet">
@endpush

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Add Product</h4>
                            </div>
                        </div>
                        <div class="iq-card-body px-4">
                            <form action="{{ route('user.product.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        <label for="name" class="required">Name</label>
                                        <input type="text" required class="form-control required" id="name"
                                               name="name" placeholder="Product Name" value="{{ old('name') }}">
                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <label for="original_price" class="required">Original Price</label>
                                        <input type="text" required class="form-control required" id="original_price"
                                               name="original_price" placeholder="200" value="{{ old('original_price') }}">
                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <label for="sale_price">Sale price</label>
                                        <input type="text" class="form-control" id="sale_price"
                                               name="sale_price" placeholder="200" value="{{ old('sale_price') }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        <label for="description" class="required">Description</label>
                                        <textarea required class="form-control required" id="description" name="description" placeholder="Description...">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        <label for="product-images" class="required">Product images</label>
                                        <input type="file" class="filepond" id="product-images" name="images[]" multiple
                                               accept="image/*">
                                        <small class="form-text text-muted">
                                            You can upload multiple images. First image will be used as the main thumbnail.
                                        </small>
                                    </div>
                                </div>
                                <div class="row text-right">
                                    <div class="col-12">
                                        <a href="{{ route('user.shop') }}" class="btn btn-secondary mr-2">Back to shop</a>
                                        <button type="submit" class="btn btn-primary">Create product</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.FilePond) {
                var input = document.querySelector('input.filepond[name="images[]"]');
                if (input) {
                    FilePond.create(input, {
                        allowMultiple: true,
                        credits: false,
                        allowReorder: true,
                        labelIdle: 'Drop product images here or <span class="filepond--label-action">Browse</span>',
                    });
                }
            }
        });
    </script>
@endpush

