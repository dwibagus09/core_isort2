
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">ChartJs</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Charts</a></li>
                                <li class="breadcrumb-item active" aria-current="page">ChartJS</li>
                            </ol>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- ROW-1 OPEN -->
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Line Chart</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="chartLine" class="h-275"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Area Chart</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="chartArea" class="h-275"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Bar Chart1</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="chartBar1" class="h-275"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Bar Chart2</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="chartBar2" class="h-275"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Donut Chart</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="chartPie" class="h-275"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Pie Chart</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="chartDonut" class="h-275"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Radar Chart</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="chartRadar" class="h-275"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Polar Chart</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="chartPolar" class="h-275"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ROW-1 CLOSE -->

@endsection

@section('scripts')

		<!-- CHARTJS JS -->
		<script src="{{asset('build/assets/plugins/chart/Chart.bundle.js')}}"></script>
		<script src="{{asset('build/assets/plugins/chart/utils.js')}}"></script>
		@vite('resources/assets/js/chart.js')

@endsection
