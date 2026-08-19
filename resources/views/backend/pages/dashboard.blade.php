@extends('backend.layout.app')
@section('title', 'Dashboard | ' . Helper::getSettings('application_name') ?? 'Machine Tool Solution')
@section('content')
    <div class="container-fluid px-5 pt-4">
        <h4 class="mt-2">Dashboard</h4>
        @if (Auth::user()->role != 2 && Auth::user()->role != 4 && Auth::user()->role != 5)
            <div class="row mb-4 mt-4">

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card bg-purple-gradient">
                        <div class="card-body dash">
                            <div class="d-flex justify-content-end">
                                <!-- Icon on the top right -->
                                <div
                                    class="dashboard-icon-seller bg-light text-dark d-flex flex-column justify-content-center align-items-center">
                                    <i class="fa-solid fa-people-group"></i>
                                </div>
                            </div>

                            {{-- <img class="dash-img" src="{{asset('assets/img/ui/resized.png')}}" alt=""> --}}

                            <!-- Top Distributor Section on the left bottom -->
                            <div class="d-flex flex-column align-items-start">
                                <a href="{{ route('admin.student.user') }}">
                                    <p class="mb-1 top  "><b>Total Students</b></p>
                                    <!-- Assuming you have a variable for the top distributor's data named $top_distributor_value -->
                                    <h4 class="mb-1">
                                        <span class="number-font ">
                                            <span class="counter">{{ $total_students ?? 0 }}</span>
                                        </span>
                                    </h4>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card bg-sexy-blue-gradient">
                        <div class="card-body dash">
                            <div class="d-flex justify-content-end">
                                <!-- Icon on the top right -->
                                <div
                                    class="dashboard-icon-disctributor bg-light text-dark d-flex flex-column justify-content-center align-items-center">
                                    <i class="fa-solid fa-people-line"></i>
                                </div>
                            </div>

                            <!-- Top Distributor Section on the left bottom -->
                            <div class="d-flex flex-column align-items-start">
                                <a href="{{ route('admin.teacher.user') }}">
                                    <p class="mb-1 top  "><b>Total Teachers</b></p>
                                    <!-- Assuming you have a variable for the top distributor's data named $top_distributor_value -->
                                    <h4 class="mb-1">
                                        <span class="number-font ">
                                            <span class="counter">{{ $total_teachers ?? 0 }}</span>
                                        </span>
                                    </h4>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card bg-cherry-gradient">
                        <div class="card-body dash">
                            <!-- Icon on the top right -->
                            <div class="d-flex justify-content-end">
                                <div
                                    class="dashboard-icon-products bg-light text-dark d-flex flex-column justify-content-center align-items-center">
                                    <i class="fa fa-cube"></i>
                                </div>
                            </div>

                            <!-- Total Products Section on the left bottom -->
                            <div class="d-flex flex-column align-items-start">
                                <a href="{{ route('admin.alumni.user') }}">
                                    <p class="mb-1 top"><b>Total Alumni</b></p>
                                    <!-- Assuming you have a variable for the total products data named $total_product -->
                                    <h4 class="mb-1">
                                        <span class="number-font ">
                                            <span class="counter">{{ $total_alumnis ?? 0 }}</span>
                                        </span>
                                    </h4>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                    <div class="card bg-mauve-gradient">
                        <div class="card-body dash">
                            <!-- Icon on the top right -->
                            <div class="d-flex justify-content-end">
                                <div
                                    class="dashboard-icon-services bg-light text-dark d-flex flex-column justify-content-center align-items-center">
                                    <i class="fa-solid fa-screwdriver-wrench"></i>
                                </div>
                            </div>

                            <!-- Total Services Section on the left bottom -->
                            <div class="d-flex flex-column align-items-start ">
                                <a href="{{ route('admin.news') }}">
                                    <p class="mb-1 top"><b>Total News</b></p>
                                    <!-- Assuming you have a variable for the total services data named $total_service -->
                                    <h4 class="mb-1">
                                        <span class="number-font ">
                                            <span class="counter">{{ $total_news ?? 0 }}</span>
                                        </span>
                                    </h4>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif

        <div class="row mt-2">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card bg-lush-gradient">
                    <div class="card-body dash">
                        <!-- Icon on the top right -->
                        <div class="d-flex justify-content-end">
                            <div
                                class="dashboard-icon-order bg-light text-dark d-flex flex-column justify-content-center align-items-center">
                                <i class="fa-solid fa-chart-simple"></i>
                            </div>
                        </div>

                        <!-- Total Order Section on the left bottom -->
                        <div class="d-flex flex-column align-items-start ">
                            <a href="{{ route('admin.event') }}">
                                <p class="mb-1 top">Total Events</p>
                                <!-- Assuming you have a variable for the total order data named $total_order -->
                                <h4 class="mb-1">
                                    <span class="number-font">
                                        <span class="counter"><b>{{ $total_events ?? 0 }}</b></span>
                                    </span>
                                </h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card bg-piglet-gradient">
                    <div class="card-body dash">
                        <!-- Icon on the top right -->
                        <div class="d-flex justify-content-end">
                            <div class="dashboard-icon-inquiry bg-light text-dark d-flex flex-column justify-content-center align-items-center">
                                <i class="fa-solid fa-envelope-open-text"></i>
                            </div>
                        </div>

                        <!-- Inquiry Request Section on the left bottom -->
                        <div class="d-flex flex-column align-items-start">
                            <p class="mb-1 top">Inquiry Request</p>
                            <!-- Assuming you have a variable for the inquiry request data named $inquiry_request -->
                            <h4 class="mb-1">
                                <span class="number-font">
                                    <span class="counter"><b>{{ $inquiry_request }}</b></span>
                                </span>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card bg-orange-gradient">
                    <div class="card-body dash">
                        <!-- Icon on the top right -->
                        <div class="d-flex justify-content-end">
                            <div class="dashboard-icon-service_order bg-light text-dark d-flex flex-column justify-content-center align-items-center">
                                <i class="fa-solid fa-people-carry-box"></i>
                            </div>
                        </div>

                        <!-- Service Order Section on the left bottom -->
                        <div class="d-flex flex-column align-items-start">
                            <p class="mb-1 top">Service Order</p>
                            <!-- Assuming you have a variable for the service order data named $service_order -->
                            <h4 class="mb-1">
                                <span class="number-font">
                                    <span class="counter"><b>{{ $service_order }}</b></span>
                                </span>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card bg-scooter-gradient">
                    <div class="card-body dash">
                        <!-- Icon on the top right -->
                        <div class="d-flex justify-content-end">
                            <div class="dashboard-icon-sales bg-light text-dark d-flex flex-column justify-content-center align-items-center">
                                <i class="fa-solid fa-hand-holding-dollar"></i>
                            </div>
                        </div>

                        <!-- Total Sales Section on the left bottom -->
                        <div class="d-flex flex-column align-items-start">
                            <p class="mb-1 top">
                                @if (Auth::user()->role != 2 && Auth::user()->role != 4 && Auth::user()->role != 5)
                                    Total Sales
                                @else
                                    Total Purchase
                                @endif
                            </p>
                            <!-- Assuming you have a variable for the total sales data named $total_sale -->
                            <h4 class="mb-1">
                                <span class="number-font">
                                    <span class="counter"><b>$ {{ $total_sale }}</b></span>
                                </span>
                            </h4>
                        </div>
                    </div>
                </div>
            </div> --}}

        </div>
    </div>
@endsection