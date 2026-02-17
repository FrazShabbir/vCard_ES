@extends('user.main')
@section('title', 'My Profile - ' . config('app.name'))

@section('styles')

@endsection

@push('css')
@endpush



@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-8">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">My Profile</h4>
                            </div>
                        </div>
                        <div class="iq-card-body px-4">
                            <form action="{{ route('user.profile.save') }}" method="POST">
                                @csrf
                                {{ @method_field('PUT') }}
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <label for="first_name">First Name</label>
                                        <input type="text" class="form-control" name="first_name" placeholder="e.g. Ali"
                                            value="{{ Auth::user()->first_name }}">
                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" class="form-control" name="last_name" placeholder="e.g. Raza"
                                            value="{{ Auth::user()->last_name }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <label for="username">Username:</label>
                                        <input type="text" class="form-control" id="username" name="username"
                                            placeholder="e.g. aliraza12" value="{{ Auth::user()->username }}" disabled>
                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <label for="email">Email address:</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="e.g. abc@email.com" value="{{ Auth::user()->email }}">
                                    </div>
                                </div>



                                <div class="row text-right">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary mr-3">Update</button>
                                        <a href="{{ route('users.index') }}" class="btn iq-bg-danger">Cancel</a>

                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">My QR</h4>
                            </div>
                        </div>
                        <div class="iq-card-body text-center">
                            {!! QrCode::eye('circle')->size(200)->generate(env('APP_URL') . auth()->user()->username) !!}

                            <div class="mt-3">
                                <span
                                    class="badge border border-primary text-{{ auth()->user()->status == 1 ? 'primary' : 'danger' }}">{{ auth()->user()->status == 1 ? 'ACTIVE' : 'SUSPENDED' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">My Profile</h4>
                            </div>
                        </div>
                        <div class="iq-card-body px-4">
                            <form action="{{ route('user.profile.save') }}" method="POST">
                                @csrf
                                {{ @method_field('PUT') }}

                                <div class="row">
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <label for="organization">Organization</label>
                                        <input type="text" class="form-control" name="organization"
                                            placeholder="e.g. Google" value="{{ Auth::user()->profile->organization }}">
                                    </div>

                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <label for="designation">Designation</label>
                                        <input type="text" class="form-control" name="designation"
                                            placeholder="e.g. Engineer" value="{{ Auth::user()->profile->designation }}">
                                    </div>

                                </div>


                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        <label for="website">Website</label>
                                        <input type="url" class="form-control" name="website"
                                            placeholder="e.g. https://google.com"
                                            value="{{ Auth::user()->profile->website }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-sm-12 mb-3">
                                        <label for="bio">BIO</label>
                                        <textarea class="form-control" name="bio" placeholder="e.g. Tell about yourself.">{{ Auth::user()->profile->bio }}</textarea>
                                    </div>

                                </div>
                                <div class="row text-right">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary mr-3">Update</button>
                                        <a href="{{ route('users.index') }}" class="btn iq-bg-danger">Cancel</a>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>


                <div class="col-sm-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">My Address Book</h4>
                            </div>
                        </div>
                        <div class="iq-card-body px-4">
                            @if ($addresses->count() > 0)
                                <div class="row mb-2">
                                    <table>

                                        <table class="table">
                                            <thead class="table-striped">
                                                <tr>
                                                    <th scope="col">Street Address</th>
                                                    <th scope="col">Flat No</th>
                                                    <th scope="col">Phone</th>
                                                    <th scope="col">Postal Code</th>
                                                    <th scope="col">Country</th>
                                                    <th scope="col">State</th>
                                                    <th scope="col">City</th>
                                                    <th scope="col">Address Type</th>
                                                    <th scope="col">Is Primary</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($addresses as $address)
                                                    <tr>
                                                        <td> <a href="javascript:void(0)" class="data-edit"
                                                                data-id="{{ $address->id }}">{{ $address->street_one }}</a>
                                                        </td>
                                                        <td>{{ $address->flat_no }}</td>
                                                        <td>{{ $address->phone }}</td>
                                                        <td>{{ $address->postal_code }}</td>
                                                        <td>{{ $address->country->name }}</td>
                                                        <td>{{ $address->state->name }}</td>
                                                        <td>{{ $address->city->name }}</td>
                                                        <td>{{ $address->address_type }}</td>
                                                        <td> <span
                                                                class="badge badge-{{ $address->is_primary == 1 ? 'primary' : 'info' }}">{{ $address->is_primary == 1 ? 'Active' : 'Not Active' }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>

                                </div>

                            @endif
                            <div class="row">


                                <form action="{{ route('address.new') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label for="street_one">Street Address*</label>
                                            <input type="text" class="form-control" name="street_one"
                                                placeholder="e.g. Google" value="{{ old('street_one') }}" required>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label for="street_two">Street Address two</label>
                                            <input type="text" class="form-control" name="street_two"
                                                placeholder="e.g. Google" value="{{ old('street_two') }}">
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label for="flat_no">Flat No*</label>
                                            <input type="text" class="form-control" name="flat_no"
                                                placeholder="e.g. 12" value="{{ old('flat_no') }}" required>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label for="phone">phone*</label>
                                            <input type="text" class="form-control" name="phone"
                                                placeholder="e.g. 92312345678" value="{{ old('phone') }}" required>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label for="postal_code">postal_code*</label>
                                            <input type="text" class="form-control" name="postal_code"
                                                placeholder="e.g. 92312345678" value="{{ old('postal_code') }}" required>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label for="country_id">Country*</label>
                                            <select name="country_id" id="country_id" class="form-control"
                                                required></select>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label for="state_id">State*</label>
                                            <select name="state_id" id="state_id" class="form-control"
                                                required></select>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label for="city_id">City*</label>
                                            <select name="city_id" id="city_id" class="form-control" required></select>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label for="address_type">Address Type</label>
                                            <select name="address_type" id="address_type" class="form-control" required>
                                                <option value="home">Home</option>
                                                <option value="office">Office</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 col-sm-12 mb-3">
                                            <label for="additional_info">Additional Info</label>
                                            <input type="text" class="form-control" name="additional_info"
                                                placeholder="e.g. Any additional info"
                                                value="{{ old('additional_info') }}">
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <label for="is_primary">Make Primary</label>
                                            <select name="is_primary" id="is_primary" class="form-control" required>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                    </div>



                                    <div class="row text-right">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary mr-3">Update</button>
                                            <a href="{{ route('users.index') }}" class="btn iq-bg-danger">Cancel</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Media</h4>
                            </div>
                        </div>
                        <div class="iq-card-body px-4">
                            <form action="{{ route('user.profile.save') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                {{ @method_field('put') }}


                                <div class="row">
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <div class="form-group">
                                            <p class="required">Profile Picture</p>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="avatar"
                                                    id="data_image_1" required>
                                                <label class="custom-file-label" for="image">Choose Profile Picture
                                                    (.png,.jpeg,jpg)</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        {{-- <img src="{{asset(auth()->user()->avatar)}}" alt="Avatar" class="img-fluid" style="max-width: 200px"> --}}

                                        <img id="uploadedImage_data_image_1"
                                            class="img-preview img_modal width_400 img-fluid" style="max-height: 200px"
                                            src="{{ asset(auth()->user()->profile->avatar) }}" alt=""
                                            accept="image/png, image/jpeg">

                                    </div>

                                </div>



                                <div class="row">
                                    <div class="col-md-6 col-sm-12 mb-3">
                                        <div class="form-group">
                                            <p class="required">Profile Banner</p>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="cover_image"
                                                    id="data_image">
                                                <label class="custom-file-label" for="image">Choose Profile Banner
                                                    (.png,.jpeg,jpg)</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-3">

                                        <img id="uploadedImage_data_image" style="max-height: 200px"
                                            class="img-preview img_modal width_400 img-fluid"
                                            src="{{ asset(auth()->user()->profile->cover_image) }}" alt=""
                                            accept="image/png, image/jpeg">

                                    </div>

                                </div>





                                <button type="submit" class="btn btn-primary mr-3">Submit</button>
                                <a href="{{ route('users.index') }}" class="btn iq-bg-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="address-data">


            </div>
        </div>
    </div>
@endsection


@section('scripts')

@endsection

@push('js')
    <script>
        document.getElementById('data_image').addEventListener('change', function() {
            if (this.files[0]) {
                var picture = new FileReader();
                picture.readAsDataURL(this.files[0]);
                picture.addEventListener('load', function(event) {
                    document.getElementById('uploadedImage_data_image').setAttribute('src',
                        event.target.result);
                    document.getElementById('uploadedImage_data_image').style.display =
                        'block';
                });
            }
        });


        document.getElementById('data_image_1').addEventListener('change', function() {
            if (this.files[0]) {
                var picture = new FileReader();
                picture.readAsDataURL(this.files[0]);
                picture.addEventListener('load', function(event) {
                    document.getElementById('uploadedImage_data_image_1').setAttribute('src',
                        event.target.result);
                    document.getElementById('uploadedImage_data_image_1').style.display =
                        'block';
                });
            }
        });



        $(document).on('click', '.data-edit', function() {

            let id = $(this).data('id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                type: "POST",
                url: "{{ route('address.get') }}",
                data: {
                    id: id,
                },
                dataType: 'html',
                success: function(data) {
                    $('.bd-example-modal-lg').modal('show');
                    $('#address-data').html(data);
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        });




        $(document).ready(function() {
            // Initialize the country select2
            $('#country_id').select2({
                placeholder: 'Select Country',
                allowClear: true,
                closeOnSelect: true,
                ajax: {
                    url: "{{ route('get.countries') }}",
                    dataType: 'json',
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: `${item.id}`,
                                    text: `${item.name}`
                                };
                            })
                        };
                    }
                }
            });

            // Country change event
            $('#country_id').on('change', function() {
                let country_id = $(this).val();
                $('#state_id').val(null).trigger('change');
                $('#state_id').prop('disabled', true);

                $('#state_id').select2({
                    placeholder: 'Select State',
                    allowClear: true,
                    closeOnSelect: true,
                    ajax: {
                        url: "{{ route('get.states') }}",
                        dataType: 'json',
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: function(params) {
                            return {
                                country_id: country_id,
                                q: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        id: `${item.id}`,
                                        text: `${item.name}`
                                    };
                                })
                            };
                        }
                    }
                });

                $('#state_id').prop('disabled', false);
            });

            // State change event
            $('#state_id').on('change', function() {
                let state_id = $(this).val();
                $('#city_id').val(null).trigger('change');
                $('#city_id').prop('disabled', true);

                $('#city_id').select2({
                    placeholder: 'Select City',
                    allowClear: true,
                    closeOnSelect: true,
                    ajax: {
                        url: "{{ route('get.cities') }}",
                        dataType: 'json',
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: function(params) {
                            return {
                                state_id: state_id,
                                q: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        id: `${item.id}`,
                                        text: `${item.name}`
                                    };
                                })
                            };
                        }
                    }
                });

                $('#city_id').prop('disabled', false);
            });
        });
    </script>
@endpush
