
@extends('layouts.custom-master')

@section('styles')



@endsection

@section('content')

            <div class="">
				<!-- CONTAINER OPEN -->
				<div class="col col-login mx-auto mt-7">
					<div class="text-center">
						<a href="{{url('index')}}">
							<img src="{{asset('build/assets/images/brand/logo.png')}}" class="header-brand-img" alt="logo">
						</a>
					</div>
				</div>
				<div class="container-login100">
					<div class="wrap-login100 p-6">
						<form class="login100-form validate-form">
							<span class="login100-form-title">
								Registration
							</span>
							<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
								<input class="input100" type="text" name="email" placeholder="User name">
								<span class="focus-input100"></span>
								<span class="symbol-input100">
									<i class="mdi mdi-account" aria-hidden="true"></i>
								</span>
							</div>
							<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
								<input class="input100" type="text" name="email" placeholder="Email">
								<span class="focus-input100"></span>
								<span class="symbol-input100">
									<i class="zmdi zmdi-email" aria-hidden="true"></i>
								</span>
							</div>
							<div class="wrap-input100 validate-input" data-validate = "Password is required">
								<input class="input100" type="password" name="pass" placeholder="Password">
								<span class="focus-input100"></span>
								<span class="symbol-input100">
									<i class="zmdi zmdi-lock" aria-hidden="true"></i>
								</span>
							</div>
							<label class="custom-control custom-checkbox mt-4">
								<input type="checkbox" class="custom-control-input">
								<span class="custom-control-label">Agree the <a href="{{url('terms')}}">terms and policy</a></span>
							</label>
							<div class="container-login100-form-btn">
								<a href="{{url('index')}}" class="login100-form-btn btn-primary">
									Register
								</a>
							</div>
							<div class="text-center pt-3">
								<p class="text-dark mb-0">Already have account?<a href="{{url('login')}}" class="text-primary ms-1">Sign In</a></p>
							</div>
							<div class=" flex-c-m text-center mt-3">
								<p>Or</p>
								<div class="social-icons">
									<ul>
										<li><a class="btn  btn-social btn-block"><i class="fa fa-google-plus text-google-plus"></i> Sign up with Google</a></li>
										<li><a class="btn  btn-social btn-block mt-2"><i class="fa fa-facebook text-facebook"></i> Sign in with Facebook</a></li>
									</ul>
								</div>
							</div>
						</form>
					</div>
				</div>
				<!-- CONTAINER CLOSED -->
			</div>

@endsection

@section('scripts')



@endsection
