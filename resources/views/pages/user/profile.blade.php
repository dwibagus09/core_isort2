
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

						<!-- PAGE-HEADER -->
						<div class="page-header">
							<h1 class="page-title">Profile</h1>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="javascript:void(0);">Pages</a></li>
								<li class="breadcrumb-item active" aria-current="page">Profile</li>
							</ol>
						</div>
						<!-- PAGE-HEADER END -->

						<!-- ROW-1 OPEN -->
						<div class="row" id="user-profile">
							<div class="col-lg-12">
								<div class="card">
									<div class="card-body">
										<div class="wideget-user">
											<div class="row">
												<div class="col-lg-6 col-md-12">
													<div class="wideget-user-desc d-flex">
														<div class="wideget-user-img">
															<img class="" src="{{ asset($user->photo) }}" alt="Profile Photo" class="img-thumbnail" width="100">
														</div>
														<div class="user-wrap">
															<h4><strong>{{ Auth::user()->name ?? 'Guest' }}</strong></h4>
															@if(Auth::check())
                                                                @if(!empty(Auth::user()->username) && !empty(Auth::user()->email))
                                                                    <h6 class="text-muted mb-3">Username: {{ Auth::user()->username }}</h6>
                                                                    <h6 class="text-muted mb-3">Email: {{ Auth::user()->email }}</h6>
                                                                @elseif(!empty(Auth::user()->username))
                                                                    <h6 class="text-muted mb-3">Username: {{ Auth::user()->username }}</h6>
                                                                @elseif(!empty(Auth::user()->email))
                                                                    <h6 class="text-muted mb-3">Email: {{ Auth::user()->email }}</h6>
                                                                @endif
                                                            @else
                                                                <h6 class="text-muted mb-3">Guest</h6>
                                                            @endif

														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="border-top">
										<div class="wideget-user-tab">
											<div class="tab-menu-heading border-0">
												<div class="tabs-menu1">
													<ul class="nav">
														<li class=""><a href="#tab-51" class="active show" data-bs-toggle="tab">Profile</a></li>
														<!--<li><a href="#tab-61" data-bs-toggle="tab" class="">Friends</a></li>-->
														<!--<li><a href="#tab-71" data-bs-toggle="tab" class="">Gallery</a></li>-->
														<!--<li><a href="#tab-81" data-bs-toggle="tab" class="">Followers</a></li>-->
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="">
									<div class="">
										<div class="border-0">
											<div class="tab-content">
												<div class="tab-pane active show" id="tab-51">
													<div class="card">
														<div class="card-body">
															<div id="profile-log-switch">
																<div class="media-heading">
																	<h5><strong>Personal Information</strong></h5>
																</div>
														  <form id="editProfileForm" enctype="multipart/form-data">
        @csrf
        <div class="mb-3" @if(in_array($user->role->role ?? '', ['Admin', 'Super Admin'])) hidden @endif >
            <label class="form-label">Email</label>
            <input type="email" class="form-control" id="editEmail" name="email" value="{{ $user->email ?? '' }}" >
        </div>
        <div class="mb-3" @if(in_array($user->role->role ?? '', ['Admin', 'Super Admin'])) hidden @endif>
            <label class="form-label">Phone</label>
            <input type="text" class="form-control" id="editPhone" name="phone" value="{{ $user->phone_no ?? '' }}" 
                >
        </div>
        <div class="mb-3">
            <label class="form-label">New Password</label>
             <div class="input-group">
            <input type="password" class="form-control edit-password" id="editPassword" name="password" placeholder="Leave blank if no changes">
              <button class="btn btn-primary" type="button" onclick="togglePassword()">
                                        <i id="eye-icon" class="fa fa-eye"></i>
             </div>                       </button>
        </div>
        <div class="mb-3">
        <label class="form-label">Confirm Password</label>
          <div class="input-group">
        <input type="password"  class="form-control confirm-password" id="confirmPassword" name="password_confirmation" placeholder="Re-enter new password">
          <button class="btn btn-primary" type="button" onclick="togglePasswordconfirm()">
                                        <i id="eye-icon-2" class="fa fa-eye"></i>
        </div>                            </button>
    </div>
    <div id="passwordMismatch" class="text-danger" style="display:none;">Passwords do not match!</div>
        <div class="mb-3">
            <label class="form-label">Upload Photo</label>
            <input type="file" class="form-control" id="editPhoto" name="photo">
           
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update Profile</button>
    </form>
																
															</div>
														</div>
												    </div>
												</div>
												
											</div>
										</div>
									</div>
								</div>
							</div><!-- COL-END -->
						</div>
						<!-- ROW-1 CLOSED -->

@endsection

@section('scripts')

		<!-- GALLERY JS -->
		<script src="{{asset('build/assets/plugins/gallery/picturefill.js')}}"></script>
        <script src="{{asset('build/assets/plugins/gallery/lightgallery.js')}}"></script>
		<script src="{{asset('build/assets/plugins/gallery/lightgallery-1.js')}}"></script>
        <script src="{{asset('build/assets/plugins/gallery/lg-pager.js')}}"></script>
        <script src="{{asset('build/assets/plugins/gallery/lg-autoplay.js')}}"></script>
        <script src="{{asset('build/assets/plugins/gallery/lg-fullscreen.js')}}"></script>
        <script src="{{asset('build/assets/plugins/gallery/lg-zoom.js')}}"></script>
        <script src="{{asset('build/assets/plugins/gallery/lg-hash.js')}}"></script>
        <script src="{{asset('build/assets/plugins/gallery/lg-share.js')}}"></script>
        
        <script>
        
     function togglePassword() {
        var passwordInput = document.getElementsByClassName("edit-password")[0];
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
    
    function togglePasswordconfirm() {
        var passwordInput = document.getElementsByClassName("confirm-password")[0];
        var eyeIcon = document.getElementById("eye-icon-2");

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
    
    // Get password and confirm password elements
    const editPassword = document.getElementById("editPassword");
    const confirmPassword = document.getElementById("confirmPassword");
    const passwordMismatch = document.getElementById("passwordMismatch");

    // Add event listener for when the user types in the confirm password field
    confirmPassword.addEventListener("input", function() {
        if (editPassword.value !== confirmPassword.value) {
            passwordMismatch.style.display = "block"; // Show mismatch message
        } else {
            passwordMismatch.style.display = "none"; // Hide mismatch message
        }
    });
</script>
        
       <script>
        document.getElementById("editProfileForm").addEventListener("submit", function(event) {
            event.preventDefault();
            
            if (editPassword.value == confirmPassword.value) {
                    
                let formData = new FormData(this);
            
                fetch("{{ route('profile.update') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                        "Accept": "application/json"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert("Update failed. Please try again.");
                    }
                })
                .catch(error => console.error("Error:", error));
            }
        });
        </script>
@endsection
