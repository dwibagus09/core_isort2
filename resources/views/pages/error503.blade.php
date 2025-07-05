
@extends('layouts.custom-master')

@section('styles')



@endsection

@section('content')

		   <!-- PAGE-CONTENT OPEN -->
           <div class="page-content error-page">
				<div class="container text-center">
					<div class="error-template">
						<h1 class="display-1 text-white mb-2">503<span class="text-transparent fs-20">error</span></h1>
						<h5 class="error-details text-white">
							Sorry, an error has occured, Requested page not found!
						</h5>
						<div class="text-center">
							<a class="btn btn-secondary mt-5 mb-5" href="{{url('index')}}"> <i class="fa fa-long-arrow-left"></i> Back to Home </a>
						</div>
                    </div>
				</div>
			</div>
			<!-- PAGE-CONTENT OPEN CLOSED -->

@endsection

@section('scripts')



@endsection
