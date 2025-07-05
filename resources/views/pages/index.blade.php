
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

<!-- PAGE-HEADER -->
<div class="page-header">
	<h1 class="page-title">Dashboard</h1>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
		<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
	</ol>
</div>
<!-- PAGE-HEADER END -->

<!-- ROW-1 -->
<!--<div class="row">-->
<!--				<div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">-->
<!--				<div class="card">-->
<!--					<div class="card-body statistics-info">-->
<!--						<h6 class="mt-4 mb-1">Please Select Site</h6>-->
<!--						<form>-->

<!--                                                    @foreach($sites as $site)-->
<!--                                                     <label class="custom-control custom-radio">-->
<!--                                                         <input type="radio" class="custom-control-input" name="radios" value="{{ $site->site_id }}">-->
<!--                                                         <span class="custom-control-label">{{ $site->site_name }}</span>-->
<!--                                                     </label>-->
<!--                                                 @endforeach-->

<!--                                             </form>-->
<!--					 <button class="btn btn-pill btn-info ">Select This Site</button>-->
<!--					</div>-->
<!--				</div>-->
<!--			</div>-->
<!--</div>-->
<!-- ROW-1 END -->



@endsection

@section('scripts')

<!-- SPARKLINE JS-->
<script src="{{asset('build/assets/plugins/jquery/jquery.sparkline.min.js')}}"></script>

<!-- CHARTJS CHART JS-->
<script src="{{asset('build/assets/plugins/chart/Chart.bundle.js')}}"></script>
<script src="{{asset('build/assets/plugins/chart/utils.js')}}"></script>

<!-- ECHART JS-->
<script src="{{asset('build/assets/plugins/echarts/echarts.js')}}"></script>


<!-- SELECT2 JS -->
<script src="{{asset('build/assets/plugins/select2/select2.full.min.js')}}"></script>
@vite('resources/assets/js/select2.js')

<!-- DATA TABLE JS-->
<script src="{{asset('build/assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('build/assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
<script src="{{asset('build/assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('build/assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>

<!-- APEXCHART JS -->
@vite('resources/assets/js/apexcharts.js')

<!-- INDEX JS -->
<!-- @vite('resources/assets/js/index1.js') -->

@endsection
