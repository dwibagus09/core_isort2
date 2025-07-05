
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Leaflet Maps</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Maps</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Leaflet Maps</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- Row -->
                            <div class="row row-sm">
                                <div class="col-lg-12">
                                    <div class="card mg-b-20" id="map">
                                        <div class="card-header">
                                            <div class="card-title">Basic</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="h-300p" id="leaflet1"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="card mg-b-20" id="map2">
                                        <div class="card-header">
                                            <div class="card-title">With Popup</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="h-300p" id="leaflet2"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="card" id="map1">
                                        <div class="card-header">
                                            <div class="card-title">Map With Circle</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="h-300p" id="leaflet3"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Row -->

@endsection

@section('scripts')

		<!-- INTERNAL LEAFLET JS -->
		<script src="{{asset('build/assets/plugins/leaflet/leaflet.js')}}"></script>
		@vite('resources/assets/js/map-leafleft.js')

@endsection
