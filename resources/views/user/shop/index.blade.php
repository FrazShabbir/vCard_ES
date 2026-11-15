@extends('user.main')
@section('title', 'Shop - ' . config('app.name'))

@section('styles')
@endsection

@push('css')
@endpush



@section('content')
    <div id="content-page" class="content-page">

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">My Shop @if ($shop)
                                        <span class="badge badge-primary">#{{ $shop->slug }}
                                        </span>
                                    @endif
                                </h4>
                            </div>
                        </div>
                        <div class="iq-card-body px-4">
                            @if ($shop == null)
                                <div class="row text-center">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target=".bd-create-shop">Create Shop</button>

                                    </div>
                                </div>
                            @else
                                <div class="table-responsive">

                                    <table id="user-list-table" class="table table-striped table-bordered mt-4"
                                        role="grid" aria-describedby="user-list-page-info">
                                        <thead>
                                            <tr>
                                                <th>Key</th>
                                                <th>Status</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr>
                                                <td>Shop Name</td>
                                                <td>{{ $shop->name }}</td>
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>URL</td>
                                                <td>{{ $shop?->slug }}</td>
                                            </tr>


                                            <tr>
                                                <td>Email</td>
                                                <td>{{ $shop?->email }}</td>

                                            </tr>

                                            <tr>
                                                <td>Phone</td>
                                                <td>{{ $shop?->phone }}</td>

                                            </tr>



                                        </tbody>
                                    </table>
                                </div>
                                <div class="row text-right">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target=".bd-update-shop">Update Shop Details</button>
                                        <a href="{{ route('user.product.create') }}" class="btn btn-primary">Add Inventory</a>
                                    </div>
                                </div>
                            @endif




                        </div>
                    </div>
                </div>





            </div>
            @if ($shop != null && $shop->products->count() > 0)
                <div class="row">
                    <div class="col-sm-12">
                        <div class="iq-card">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">My Inventory @if ($shop)
                                            <span class="badge badge-primary">#{{ $shop->slug }}
                                            </span>
                                        @endif
                                    </h4>
                                </div>
                            </div>
                            <div class="iq-card-body px-4">
                                @if ($shop != null && $shop->products->count() > 0)
                                    <div class="table-responsive">
                                        <table id="user-list-table" class="table table-striped table-bordered mt-4"
                                            role="grid" aria-describedby="user-list-page-info">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Price</th>
                                                    <th>Description</th>
                                                    <th>Action</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($shop->products as $p)
                                                    <tr>
                                                        <td>{{ $p->name }}</td>
                                                        <td><del>{{ $p->original_price }}</del> {{ $p->sale_price }}</td>
                                                    <td>{{ $p->description }}</td>
                                                    <td>
                                                        <a href="{{ route('user.product.edit', $p->slug) }}" class="btn btn-primary btn-sm">Edit</a>
                                                    </td>
                                                    </tr>
                                                @endforeach





                                            </tbody>
                                        </table>
                                    </div>
                                @endif




                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>


    @if ($shop == null)
        <div class="modal fade bd-create-shop" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">

                <form action="{{ route('user.shop.store') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Shop Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <label for="name" class="required">Shop Name</label>
                                    <input type="text" required class="form-control required" id="name"
                                        name="name" placeholder="John" value="{{ auth()->user()->last_name }}">
                                </div>


                                <div class="col-md-6 col-sm-12 mb-3">
                                    <label for="email" class="required">Shop Email</label>
                                    <input type="text" required class="form-control required" id="email"
                                        name="email" placeholder="Doe" value="{{ auth()->user()->email }}">
                                </div>

                                <div class="col-md-6 col-sm-12 mb-3">
                                    <label for="phone" class="required">Shop Phone</label>
                                    <input type="text" required class="form-control required" id="phone"
                                        name="phone" placeholder="+92 311 1122334"
                                        value="{{ auth()->user()->profile->phone }}">
                                </div>
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <label for="website" class="required">Shop Website</label>
                                    <input type="url" required class="form-control required" id="website"
                                        name="website" placeholder="http://"
                                        value="{{ auth()->user()->profile->website }}">
                                </div>
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <label for="currency" class="required">Shop Currency</label>
                                    <select name="currency" id="currency" class="form-control">
                                        <option value="PKR">PKR</option>
                                        <option value="USD">USD</option>
                                        <option value="GBP">GBP</option>
                                        <option value="EUR">EUR</option>
                                    </select>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 mb-3">
                                    <label for="address" class="required">Shop Address</label>
                                    <textarea required class="form-control required" id="address" name="address" placeholder="Shop Address"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create Shop</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    @else
        <div class="modal fade bd-update-shop" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">

                <form action="{{ route('user.shop.update', $shop->slug) }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Shop Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <label for="name" class="required">Shop Name</label>
                                    <input type="text" required class="form-control required" id="name"
                                        name="name" placeholder="John" value="{{ $shop->name }}">
                                </div>


                                <div class="col-md-6 col-sm-12 mb-3">
                                    <label for="email" class="required">Shop Email</label>
                                    <input type="text" required class="form-control required" id="email"
                                        name="email" placeholder="Doe" value="{{ $shop->email }}">
                                </div>

                                <div class="col-md-6 col-sm-12 mb-3">
                                    <label for="phone" class="required">Shop Phone</label>
                                    <input type="text" required class="form-control required" id="phone"
                                        name="phone" placeholder="+92 311 1122334" value="{{ $shop->phone }}">
                                </div>
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <label for="website" class="required">Shop Website</label>
                                    <input type="url" required class="form-control required" id="website"
                                        name="website" placeholder="http://" value="{{ $shop->website }}">
                                </div>
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <label for="currency" class="required">Shop Currency</label>
                                    <select name="currency" id="currency" class="form-control">
                                        <option value="PKR" {{ $shop->currency == 'PKR' ? 'selected' : '' }}>PKR
                                        </option>
                                        <option value="USD" {{ $shop->currency == 'USD' ? 'selected' : '' }}>USD
                                        </option>
                                        <option value="GBP" {{ $shop->currency == 'GBP' ? 'selected' : '' }}>GBP
                                        </option>
                                        <option value="EUR" {{ $shop->currency == 'EUR' ? 'selected' : '' }}>EUR
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 mb-3">
                                    <label for="address" class="required">Shop Address</label>
                                    <textarea required class="form-control required" id="address" name="address" placeholder="Shop Address">{{ $shop->address }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Shop</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div class="modal fade bd-inventory-upload" tabindex="-1" role="dialog" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('user.product.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Product</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 mb-3">
                                    <label for="name" class="required">Name</label>
                                    <input type="text" required class="form-control required" id="name"
                                        name="name" placeholder="Product Name" value="">
                                </div>
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <label for="original_price" class="required">Original Price</label>
                                    <input type="text" required class="form-control required" id="original_price"
                                        name="original_price" placeholder="200" value="">
                                </div>

                                <div class="col-md-6 col-sm-12 mb-3">
                                    <label for="sale_price" class="required">Sale price</label>
                                    <input type="text" required class="form-control required" id="sale_price"
                                        name="sale_price" placeholder="200" value="">
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 mb-3">
                                    <label for="description" class="required">Description</label>
                                    <textarea required class="form-control required" id="description" name="description" placeholder="Description..."></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 mb-3">
                                    <label for="product-images" class="required">Product images</label>
                                    <input type="file" class="filepond" id="product-images" name="images[]" multiple
                                        accept="image/*">
                                    <small class="form-text text-muted">You can upload multiple images. First image will be used as the main thumbnail.</small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Shop</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div class="modal fade bd-inventory-edit" id="bd-inventory-edit" tabindex="-1" role="dialog"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg" id="paste-product">

            </div>
        </div>
    @endif
@endsection


@section('scripts')

    <script>
        function viewData(id) {
            // legacy function no longer used; kept to avoid JS errors
        }
    </script>

@endsection

@push('js')
    {{-- FilePond for product images --}}
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.FilePond) {
                var inputs = document.querySelectorAll('input.filepond[name="images[]"]');
                Array.prototype.forEach.call(inputs, function(input) {
                    FilePond.create(input, {
                        allowMultiple: true,
                        credits: false,
                        allowReorder: true,
                        labelIdle: 'Drop product images here or <span class="filepond--label-action">Browse</span>',
                    });
                });
            }
        });
    </script>
@endpush
