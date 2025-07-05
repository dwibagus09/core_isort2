
@extends('layouts.custom-master')

@section('styles')



@endsection

@section('content')

            <div class="">
				<div class="col col-login mx-auto mt-7">
					<div class="text-center">
						<a href="{{url('index')}}">
							<img src="{{asset('build/assets/images/brand/logo.png')}}" class="header-brand-img" alt="logo">
						</a>
					</div>
				</div>
				<!-- CONTAINER OPEN -->
				<div class="container-login100">
					<div class="wrap-login100 p-5">
						<form class="login100-form validate-form ">
							<div class="text-center mb-4">
								<img src="{{asset('build/assets/images/users/10.jpg')}}" alt="lockscreen image" class="avatar avatar-xxl brround mb-2">
								<h4>Elizabeth Dyer</h4>
							</div>
							<div class="wrap-input100 validate-input" data-validate="Password is required">
								<input class="input100" type="password" name="pass" placeholder="Password">
								<span class="focus-input100"></span>
								<span class="symbol-input100">
									<i class="fa fa-lock" aria-hidden="true"></i>
								</span>
							</div>
							<div class="container-login100-form-btn pt-2">
								<a href="{{url('index')}}" class="login100-form-btn btn-primary">
									Unlock
								</a>
							</div>
							<div class="text-center pt-2">
								<span class="txt1">
									I Forgot
								</span>
								<a class="txt2" href="{{url('forgot-password')}}">
									Give me Hint
								</a>
							</div>
						</form>
					</div>
				</div>
				<!-- CONTAINER CLOSED -->
			</div>

@endsection

@section('scripts')



@endsection
