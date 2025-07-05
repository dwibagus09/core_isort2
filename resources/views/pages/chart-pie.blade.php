
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">C3-Pie Charts</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Charts</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">C3-Pie Charts</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW-1 OPEN -->
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Pie Chart</h3>
                                        </div>
                                        <div class="card-body">
                                            <div id="chart-pie" class="chartsh"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Pie Chart with Multiple colors</h3>
                                        </div>
                                        <div class="card-body">
                                            <div id="chart-pie2" class="chartsh"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-1 CLOSE -->

                            <!-- ROW-2 OPEN -->
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Pie Chart3</h3>
                                        </div>
                                        <div class="card-body">
                                            <div id="chart-pie3" class="chartsh"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Pie Chart4</h3>
                                        </div>
                                        <div class="card-body">
                                            <div id="chart-pie4" class="chartsh"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-2 CLOSE -->

@endsection

@section('scripts')

		<!-- C3 CHART JS -->
		<script src="{{asset('build/assets/plugins/charts-c3/d3.v5.min.js')}}"></script>
		<script src="{{asset('build/assets/plugins/charts-c3/c3-chart.js')}}"></script>

		<!-- C3-PIE CHART JS -->
		@vite('resources/assets/js/charts.js')

@endsection
