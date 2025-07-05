
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">RangeSlider</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Components</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">RangeSlider</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!--div-->
                            <div class="card overflow-hidden">
                                <div class="card-header border-bottom-0">
                                    <div class="card-title">
                                        Range Slider
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row row-sm">
                                        <div class="col-lg-12">
                                            <input class="rangeslider1" name="example_name" type="text" value="">
                                        </div>
                                        <div class="col-lg-12 mt-4">
                                            <input class="rangeslider2" name="example_name" type="text" value="">
                                        </div>
                                    </div>
                                    <div class="row row-sm">
                                        <div class="col-lg-12 mt-4">
                                            <input class="rangeslider3" name="example_name" type="text" value="">
                                        </div>
                                        <div class="col-lg-12 mt-4">
                                            <input class="rangeslider4" name="example_name" type="text" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/div-->

                            <!--div-->
                            <div class="card overflow-hidden">
                                <div class="card-header border-bottom-0">
                                    <div class="card-title">
                                        Range Slider (Modern Skin)
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row row-sm">
                                        <div class="col-lg-12">
                                            <input class="rangeslider1" data-extra-classes="irs-modern" name="example_name" type="text">
                                        </div>
                                        <div class="col-lg-12 mt-4">
                                            <input class="rangeslider2" data-extra-classes="irs-modern" name="example_name" type="text">
                                        </div>
                                    </div>
                                    <div class="row row-sm">
                                        <div class="col-lg-12 mt-4">
                                            <input class="rangeslider3" data-extra-classes="irs-modern" name="example_name" type="text">
                                        </div>
                                        <div class="col-lg-12 mt-4">
                                            <input class="rangeslider4" data-extra-classes="irs-modern" name="example_name" type="text">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/div-->

                            <!--div-->
                            <div class="card overflow-hidden">
                                <div class="card-header border-bottom-0">
                                    <div class="card-title">
                                        Range Slider (Outline Skin)
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row row-sm">
                                        <div class="col-lg-12">
                                            <input class="rangeslider1" data-extra-classes="irs-outline" name="example_name" type="text">
                                        </div>
                                        <div class="col-lg-12 mt-4">
                                            <input class="rangeslider2" data-extra-classes="irs-outline" name="example_name" type="text">
                                        </div>
                                    </div>
                                    <div class="row row-sm">
                                        <div class="col-lg-12 mt-4">
                                            <input class="rangeslider3" data-extra-classes="irs-outline" name="example_name" type="text">
                                        </div>
                                        <div class="col-lg-12 mt-4">
                                            <input class="rangeslider4" data-extra-classes="irs-outline" name="example_name" type="text">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/div-->

@endsection

@section('scripts')

		<!-- ION-RANGESLIDER JS -->
		<script src="{{asset('build/assets/plugins/ion.rangeSlider/js/ion.rangeSlider.min.js')}}"></script>

		<!-- RANGESILDER JS -->
		@vite('resources/assets/js/range.js')

@endsection
