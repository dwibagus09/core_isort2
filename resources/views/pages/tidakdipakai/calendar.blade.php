
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Default Calender</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Components</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Default Calender</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW OPEN-->
                            <div class="">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="cal1"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW CLOSE-->

@endsection

@section('scripts')

		<!-- DEFAULT CALENDAR JS-->
		<script src="{{asset('build/assets/plugins/calendar/underscore-min.js')}}"></script>
		<script src="{{asset('build/assets/plugins/calendar/moment.js')}}"></script>
		<script src="{{asset('build/assets/plugins/calendar/clndr.js')}}"></script>
		<script src="{{asset('build/assets/plugins/calendar/demo.js')}}"></script>

@endsection
