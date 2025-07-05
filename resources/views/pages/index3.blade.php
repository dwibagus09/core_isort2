
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Dashboard 03</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Dashboard 03</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW-1 OPEN -->
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                                    <div class="card bg-primary img-card box-primary-shadow">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="text-white">
                                                    <h2 class="mb-0 number-font">23,536</h2>
                                                    <p class="text-white mb-0">Total Requests </p>
                                                </div>
                                                <div class="ms-auto"> <i class="fa fa-send-o text-white fs-30 me-2 mt-2"></i> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL END -->
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                                    <div class="card bg-secondary img-card box-secondary-shadow">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="text-white">
                                                    <h2 class="mb-0 number-font">45,789</h2>
                                                    <p class="text-white mb-0">Total Revenue</p>
                                                </div>
                                                <div class="ms-auto"> <i class="fa fa-bar-chart text-white fs-30 me-2 mt-2"></i> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL END -->
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                                    <div class="card  bg-success img-card box-success-shadow">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="text-white">
                                                    <h2 class="mb-0 number-font">89,786</h2>
                                                    <p class="text-white mb-0">Total Profit</p>
                                                </div>
                                                <div class="ms-auto"> <i class="fa fa-dollar text-white fs-30 me-2 mt-2"></i> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL END -->
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                                    <div class="card bg-info img-card box-info-shadow">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="text-white">
                                                    <h2 class="mb-0 number-font">43,336</h2>
                                                    <p class="text-white mb-0">Monthly Sales</p>
                                                </div>
                                                <div class="ms-auto"> <i class="fa fa-cart-plus text-white fs-30 me-2 mt-2"></i> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL END -->
                            </div>
                            <!-- ROW-1 CLOSED -->

                            <!-- ROW-2 OPEN -->
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-6">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-6 ">
                                            <div class="card overflow-hidden">
                                                <div class="card-body ">
                                                    <h5 class="mb-1">Total Price</h5>
                                                    <h3 class="text-dark count mt-0 number-font">4,657</h3>
                                                    <div class="progress progress-sm mt-0 mb-2">
                                                        <div class="progress-bar bg-primary w-75" role="progressbar"></div>
                                                    </div>
                                                    <div class=""><i class="fa fa-caret-up text-green mx-1"></i>10% increases</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=" col-sm-12 col-md-6 col-lg-6">
                                            <div class="card overflow-hidden">
                                                <div class="card-body ">
                                                    <h5 class="mb-1">Total Stock</h5>
                                                    <h3 class="text-dark count mt-0 number-font">2,592</h3>
                                                    <div class="progress progress-sm mt-0 mb-2">
                                                        <div class="progress-bar bg-secondary w-45" role="progressbar"></div>
                                                    </div>
                                                    <div class=""><i class="fa fa-caret-down text-danger mx-1"></i>12% decrease</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=" col-sm-12 col-md-6 col-lg-6">
                                            <div class="card overflow-hidden">
                                                <div class="card-body ">
                                                    <h5 class="mb-1">Total Revenue</h5>
                                                    <h3 class="text-dark count mt-0 number-font">3,517</h3>
                                                    <div class="progress progress-sm mt-0 mb-2">
                                                        <div class="progress-bar bg-success w-50" role="progressbar"></div>
                                                    </div>
                                                    <div class=""><i class="fa fa-caret-down text-danger mx-1"></i>5% decrease</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=" col-sm-12 col-md-6  col-lg-6 ">
                                            <div class="card overflow-hidden">
                                                <div class="card-body ">
                                                    <h5 class="mb-1">Total Investment</h5>
                                                    <h3 class="text-dark count mt-0 font-30 number-font">5,759</h3>
                                                    <div class="progress progress-sm mt-0 mb-2">
                                                        <div class="progress-bar bg-info w-25" role="progressbar"></div>
                                                    </div>
                                                    <div class=""><i class="fa fa-caret-up text-success mx-1"></i>15% increase</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Revenue Vs Support Cost</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="chart-wrapper ">
                                                <canvas id="revenue" class="areaChart chart-dropshadow"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL END -->
                            </div>
                            <!-- ROW-2 CLOSED -->

                            <!-- ROW-3 OPEN -->
                            <div class="row row-cards">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-5">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Customer satisfaction</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="Order-Status-chart my-4 chart-wrapper">
                                                <div class="Order-Status-inner text-center">
                                                    <h4 class="fs-42 mb-0 op-6">98k</h4>
                                                    <div class="text-muted op-7">Total Orders</div>
                                                </div>
                                                <div id="Order-Status" class="Order-Status overflow-hidden mx-auto"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL END -->
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-7">
                                    <div class="card">
                                        <div class="card-header ">
                                            <h3 class="card-title">Rating Record</h3>
                                        </div>
                                        <div class="card-body rating-record">
                                            <div class="table-responsive service">
                                                <table class="table table-bordered table-hover  mb-0 text-nowrap ">
                                                    <thead>
                                                        <tr>
                                                            <th>Total calls</th>
                                                            <th>Trend</th>
                                                            <th>Call Duration</th>
                                                            <th>Resolution Rate</th>
                                                            <th>Satisfaction Rate</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>5,245</td>
                                                            <td>Ryan MacLeod</td>
                                                            <td>5min</td>
                                                            <td>
                                                                <div class="progress progress-md mt-1 h-2">
                                                                    <div class="progress-bar bg-primary w-50" role="progressbar"></div>
                                                                </div>
                                                            </td>
                                                            <td><div class="rating-stars">
                                                                <div class="rating-stars-container d-flex justify-content-center">
                                                                    <div class="rating-star sm is--active text-warning">
                                                                        <i class="fa fa-star"></i>
                                                                    </div>
                                                                    <div class="rating-star sm is--active text-warning">
                                                                        <i class="fa fa-star"></i>
                                                                    </div>
                                                                    <div class="rating-star sm is--active text-warning">
                                                                        <i class="fa fa-star"></i>
                                                                    </div>
                                                                    <div class="rating-star sm is--active text-warning">
                                                                        <i class="fa fa-star"></i>
                                                                    </div>
                                                                    <div class="rating-star sm text-light">
                                                                        <i class="fa fa-star"></i>
                                                                    </div>
                                                                </div>
                                                            </div></td>
                                                        </tr>
                                                        <tr>
                                                            <td>4,845</td>
                                                            <td>Jacob Sutherland</td>
                                                            <td>17min</td>
                                                            <td>
                                                                <div class="progress progress-md mt-1 h-2">
                                                                    <div class="progress-bar bg-blue w-25" role="progressbar"></div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="rating-stars">
                                                                    <div class="rating-stars-container d-flex justify-content-center">
                                                                        <div class="rating-star is--active sm text-warning">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                        <div class="rating-star is--active sm text-warning">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                        <div class="rating-star is--active sm text-warning">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                        <div class="rating-star sm text-light">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                        <div class="rating-star sm text-light">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>2,736</td>
                                                            <td>James Oliver</td>
                                                            <td>12min</td>
                                                            <td>
                                                                <div class="progress progress-md mt-1 h-2">
                                                                    <div class="progress-bar bg-warning w-65" role="progressbar"></div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="rating-stars">
                                                                    <div class="rating-stars-container d-flex justify-content-center">
                                                                        <div class="rating-star sm is--active text-warning">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                        <div class="rating-star sm is--active text-warning">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                        <div class="rating-star sm is--active text-warning">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                        <div class="rating-star sm is--active text-warning">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                        <div class="rating-star sm text-light">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>3,637</td>
                                                            <td>Lisa Nash</td>
                                                            <td>25min</td>
                                                            <td>
                                                                <div class="progress progress-md mt-1 h-2">
                                                                    <div class="progress-bar bg-danger w-70" role="progressbar"></div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="rating-stars">
                                                                    <div class="rating-stars-container d-flex justify-content-center">
                                                                        <div class="rating-star sm is--active text-warning">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                        <div class="rating-star sm is--active text-warning">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                        <div class="rating-star sm is--active text-warning">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                        <div class="rating-star sm text-light">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                        <div class="rating-star sm text-light">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>6,365</td>
                                                            <td>Alan Walsh</td>
                                                            <td>10min</td>
                                                            <td>
                                                                <div class="progress progress-md mt-1 h-2">
                                                                    <div class="progress-bar bg-info w-45" role="progressbar"></div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="rating-stars">
                                                                    <div class="rating-stars-container d-flex justify-content-center">
                                                                        <div class="rating-star sm is--active text-warning">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                        <div class="rating-star sm is--active text-warning">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                        <div class="rating-star sm text-light">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                        <div class="rating-star sm text-light">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                        <div class="rating-star sm text-light">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>4,269</td>
                                                            <td>Pippa Mills</td>
                                                            <td>14min</td>
                                                            <td>
                                                                <div class="progress progress-md mt-1  h-2">
                                                                    <div class="progress-bar bg-pink w-50" role="progressbar"></div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="rating-stars">
                                                                    <div class="rating-stars-container d-flex justify-content-center">
                                                                        <div class="rating-star sm is--active text-warning">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                        <div class="rating-star sm is--active text-warning">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                        <div class="rating-star sm is--active text-warning">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                        <div class="rating-star sm is--active text-warning">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                        <div class="rating-star sm text-light">
                                                                            <i class="fa fa-star"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL END -->
                            </div>
                            <!-- ROW-3 CLOSED -->

@endsection

@section('scripts')

		<!-- C3.JS CHART JS -->
		<script src="{{asset('build/assets/plugins/charts-c3/d3.v5.min.js')}}"></script>
		<script src="{{asset('build/assets/plugins/charts-c3/c3-chart.js')}}"></script>

		<!-- CHARTJS CHART JS -->
		<script src="{{asset('build/assets/plugins/chart/Chart.bundle.js')}}"></script>
		<script src="{{asset('build/assets/plugins/chart/utils.js')}}"></script>

		<!-- Apex Charts JS -->
		@vite('resources/assets/js/apexcharts.js')

		<!-- INDEX JS -->
		@vite('resources/assets/js/index3.js')

@endsection
