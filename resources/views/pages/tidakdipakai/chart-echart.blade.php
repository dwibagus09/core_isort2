
@extends('layouts.master')

@section('styles')

@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">ECharts</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Charts</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">ECharts</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW-1 OPEN -->
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Line chart</h3>
                                        </div>
                                        <div class="card-body">
                                            <div id="echarteg1" class="chartsh"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Combination of Line & Bar Chart</h3>
                                        </div>
                                        <div class="card-body">
                                            <div id="echart2" class="chartsh"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-1 CLOSED -->

                            <!-- ROW-2 OPEN -->
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Vertical Line chart</h3>
                                        </div>
                                        <div class="card-body">
                                            <div id="echart3" class="chartsh"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Vertical Combination of Line & Bar Chart</h3>
                                        </div>
                                        <div class="card-body">
                                            <div id="echart4" class="chartsh"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-2 CLOSE -->

                            <!-- ROW-3 OPEN -->
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Bar Chart</h3>
                                        </div>
                                        <div class="card-body">
                                            <div id="echart5" class="chartsh"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Horizontal Bar Chart</h3>
                                        </div>
                                        <div class="card-body">
                                            <div id="echart6" class="chartsh"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-3 CLOSE -->

                            <!-- ROW-4 OPEN -->
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Single line chart</h3>
                                        </div>
                                        <div class="card-body">
                                            <div id="echart7" class="chartsh"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Single smooth line chart</h3>
                                        </div>
                                        <div class="card-body">
                                            <div id="echart8" class="chartsh"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-4 CLOSED -->


@endsection

@section('scripts')

		<!-- ECHART JS -->
		@vite('resources/assets/js/echarts.js')
		<script src="{{asset('build/assets/plugins/echarts/echarts.js')}}"></script>

@endsection
