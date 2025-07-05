
@extends('layouts.custom-master')

@section('styles')
<style>
html, body {
  height: 100%;
  margin: 0;
  overflow: hidden;
}

.row.full-height {
  height: 100vh;
  margin: 0;
}

.left-bg {
  width: 100%;
  float: left;
  background: url('{{ asset("build/assets/images/media/isortbg7.jpg") }}') no-repeat left bottom;
  background-size: cover;
  height: 100vh;
  border-radius: 0px 120px 0px 120px;
}

.login-container {
  width: 40%;
  float: right;
  height: 100vh;
  background: url('{{ asset("build/assets/images/media/pattern_bg.jpg") }}') no-repeat center center;
  background-size: cover;
  position: relative;
  overflow-y: auto;
}
.lefts-content{
  background: url('{{ asset("build/assets/images/media/pattern_bg.jpg") }}') no-repeat center center;
  padding-inline-end : 0;
  padding-inline-start : 0;
}

@media (max-width: 800px) {
  .left-bg {
    display: none;
  }

  .login-container {
    float: none;
    width: 100%;
  }

  .container-login100 {
    margin-top: 0 !important;
    width: 100%;
    padding-left: 35px;
    padding-right: 35px;
    display: inline-block;
  }
}

</style>
@endsection

@section('content')
<div class="row full-height">
  <div class="col-md-6 col-12 lefts-content">
    <div class="left-bg"></div>
  </div>
  <div class="col-md-6 col-12 login-container">
    <div class="" style="justify-content: center;align-items: center">
      <!-- CONTAINER OPEN -->
      <div class="col col-login mx-auto mt-9">
        <div class="text-center">
          <a href="{{url('index')}}">
            <img src="{{asset('build/assets/images/brand/isort_new_logo.png')}}" height="100px" width="100px" class="header-brand-img" alt="logo">
            <br>
            <h1><strong>
              iSort CMMS</strong></h1>
            </a>
          </div>
        </div>
        <div class="container-login100" >
          <div class="wrap-login100 p-1">
            <form class="login100-form validate-form" method="POST" action="{{ url('/postlogin') }}">
              @csrf
              <div class="wrap-input100 validate-input mb-4" data-validate="Valid Username">
                <input class="form-control" type="text" name="username" placeholder="Username" value="{{ old('username') }}">
                @error('email')
                <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>

              <div class="wrap-input100 validate-input" data-validate="Password is required">
                <div class="input-group">
                  <input id="password" class="form-control" type="password" name="pass" placeholder="Password">
                  <button class="btn btn-primary" type="button" onclick="togglePassword()">
                    <i id="eye-icon" class="fa fa-eye"></i>
                  </button>
                </div>

                @error('pass')
                <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>

              @if($errors->has('loginError'))
              <div class="text-danger mb-3">{{ $errors->first('loginError') }}</div>
              @endif

              <div class="container-login100-form-btn">
                <button type="submit" class="login100-form-btn btn-primary">Login</button>
              </div>
            </form>

          </div>

        </div>

        <!-- CONTAINER CLOSED -->
      </div>
    </div>
  </div>
  @endsection

  @section('scripts')

  <script>
  function togglePassword() {
    var passwordInput = document.getElementById("password");
    var eyeIcon = document.getElementById("eye-icon");

    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      eyeIcon.classList.remove("fa-eye");
      eyeIcon.classList.add("fa-eye-slash");
    } else {
      passwordInput.type = "password";
      eyeIcon.classList.remove("fa-eye-slash");
      eyeIcon.classList.add("fa-eye");
    }
  }
</script>

@endsection
