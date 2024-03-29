@extends('frontend.main')
@section('title', 'Index Page')

@section('styles')
@endsection

@push('css')
@endpush

@section('extra_class')

@endsection

@section('banner')
@include('frontend.partial._navbar')

<section class="hero-banner position-relative custom-pt-1 custom-pb-2 bg-dark" data-bg-img="{{asset('frontend/assets/images/bg/02.png')}}">
    <div class="container">
      <div class="row text-white text-center z-index-1">
        <div class="col">
          <h1 class="text-white">Privacy Policies</h1> 
        </div>
      </div>
      <!-- / .row -->
    </div>
    <!-- / .container -->
    <div class="shape-1 bottom">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#fff" fill-opacity="1" d="M0,288L48,288C96,288,192,288,288,266.7C384,245,480,203,576,208C672,213,768,267,864,245.3C960,224,1056,128,1152,96C1248,64,1344,96,1392,112L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
      </svg>
    </div>
  </section>
@endsection

@section('content')
<!--privacy start-->

<section>
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem error quae illo excepturi nostrum blanditiis laboriosam magnam explicabo.</p>
        <p>eum nihil expedita dolorum odio dolorem, explicabo rem illum magni perferendis. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem error quae illo excepturi nostrum blanditiis laboriosam magnam explicabo. Molestias, eum nihil expedita dolorum odio dolorem, explicabo rem illum magni perferendis.</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem error quae illo excepturi nostrum blanditiis laboriosam magnam explicabo. Molestias, eum nihil expedita dolorum odio dolorem, explicabo rem illum magni perferendis.</p>
        <h4 class="mt-5 text-primary">Personal Information</h4>
        <div class="d-flex align-items-center mb-3">
              <div class="bg-light rounded p-1">
                <i class="las la-check"></i>
              </div>
              <p class="mb-0 ms-3">Lorem ipsum dolor sit amet, consectetur</p>
            </div>
            <div class="d-flex align-items-center mb-3">
              <div class="bg-light rounded p-1">
                <i class="las la-check"></i>
              </div>
              <p class="mb-0 ms-3">Quidem error quae illo excepturi nostrum blanditiis laboriosam</p>
            </div>
            <div class="d-flex align-items-center mb-3">
              <div class="bg-light rounded p-1">
                <i class="las la-check"></i>
              </div>
              <p class="mb-0 ms-3">Molestias, eum nihil expedita dolorum odio dolorem</p>
            </div>
            <div class="d-flex align-items-center mb-3">
              <div class="bg-light rounded p-1">
                <i class="las la-check"></i>
              </div>
              <p class="mb-0 ms-3">Eum nihil expedita dolorum odio dolorem</p>
            </div>
            <div class="d-flex align-items-center">
              <div class="bg-light rounded p-1">
                <i class="las la-check"></i>
              </div>
              <p class="mb-0 ms-3">Explicabo rem illum magni perferendis</p>
            </div>
        <p class="mt-5">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Possimus, ex, quisquam. Nulla excepturi sint iusto incidunt sed omnis expedita, commodi dolores. Debitis nemo animi quia deleniti commodi nesciunt illo. Deserunt.</p>
        <h4 class="mt-5 text-primary">Use of User Information.</h4>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Possimus, ex, quisquam. Nulla excepturi sint iusto incidunt sed omnis expedita, commodi dolores. Debitis nemo animi quia deleniti commodi nesciunt illo. Deserunt.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Possimus, ex, quisquam. Nulla excepturi sint iusto incidunt sed omnis expedita, commodi dolores. Debitis nemo animi quia deleniti commodi nesciunt illo. Deserunt.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Possimus, ex, quisquam. Nulla excepturi sint iusto incidunt sed omnis expedita, commodi dolores. Debitis nemo animi quia deleniti commodi nesciunt illo. Deserunt.</p>
        <h4 class="mt-5 text-primary">Disclosure of User Information.</h4>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem ullam nostrum dolor alias aspernatur nobis suscipit eaque cumque distinctio eos, beatae deserunt, nihil nam maiores vero, eius harum. Reprehenderit, aspernatur.</p>
        <div class="d-flex align-items-center mb-3">
              <div class="bg-light rounded p-1">
                <i class="las la-check"></i>
              </div>
              <p class="mb-0 ms-3">Lorem ipsum dolor sit amet, consectetur</p>
            </div>
            <div class="d-flex align-items-center mb-3">
              <div class="bg-light rounded p-1">
                <i class="las la-check"></i>
              </div>
              <p class="mb-0 ms-3">Quidem error quae illo excepturi nostrum blanditiis laboriosam</p>
            </div>
            <div class="d-flex align-items-center mb-3">
              <div class="bg-light rounded p-1">
                <i class="las la-check"></i>
              </div>
              <p class="mb-0 ms-3">Molestias, eum nihil expedita dolorum odio dolorem</p>
            </div>
            <div class="d-flex align-items-center mb-3">
              <div class="bg-light rounded p-1">
                <i class="las la-check"></i>
              </div>
              <p class="mb-0 ms-3">Eum nihil expedita dolorum odio dolorem</p>
            </div>
            <div class="d-flex align-items-center">
              <div class="bg-light rounded p-1">
                <i class="las la-check"></i>
              </div>
              <p class="mb-0 ms-3">Explicabo rem illum magni perferendis</p>
            </div>
        <p class="mt-5 mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Possimus, ex, quisquam. Nulla excepturi sint iusto incidunt sed omnis expedita, commodi dolores. Debitis nemo animi quia deleniti commodi nesciunt illo. Deserunt.</p>
      </div>
    </div>
  </div>
</section>

<!--privacy end-->

@endsection


@section('scripts')
@endsection

@push('js')
@endpush