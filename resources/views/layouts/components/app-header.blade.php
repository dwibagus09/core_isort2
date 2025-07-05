<div class="app-header header sticky">
	<div class="container-fluid main-container">
		<div class="d-flex align-items-center">
			<a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0);"></a>
			<!-- sidebar-toggle-->
			<a class="logo-horizontal " href="{{url('index')}}">
				<img src="{{asset('build/assets/images/brand/isort_new_logo_admin_big.png')}}" height="100px" class="header-brand-img desktop-logo" alt="logo">
				<img src="{{asset('/build/assets/images/brand/isort_new_logo_admin_big.png')}}" height="40px" class="header-brand-img light-logo1" alt="logo">
			</a>
			<!-- LOGO -->
			<div class="d-flex order-lg-2 ms-auto header-right-icons">

				<button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button"
				data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4"
				aria-controls="navbarSupportedContent-4" aria-expanded="false"
				aria-label="Toggle navigation">
				<span class="navbar-toggler-icon fe fe-more-vertical"></span>
			</button>

			<div class="navbar navbar-collapse responsive-navbar p-0">
				<div class="collapse navbar-collapse navbarSupportedContent-4" id="navbarSupportedContent-4">
					<div class="d-flex order-lg-2">

						<select class="form-control select" id="select-branch" style="width: 150px; margin-left:10px;">
							<option value="all" {{ session('selected_branch') == 'all' ? 'selected' : '' }}>All Sites</option>
							@foreach ($sites as $site)
							<option value="{{ $site->site_id }}" {{ session('selected_branch') == $site->site_id ? 'selected' : '' }}>
								{{ $site->site_name }}
							</option> <!-- Ganti 'nama_field' dengan nama kolom yang ingin Anda tampilkan -->
							@endforeach
						</select>

						<div class="d-flex">
							<a class="nav-link icon theme-layout nav-link-bg layout-setting">
								<span class="dark-layout"><i class="fe fe-moon"></i></span>
								<span class="light-layout"><i class="fe fe-sun"></i></span>
							</a>
						</div>
						<!-- Theme-Layout -->

						<div class="dropdown d-flex header-settings">
							<a href="javascript:void(0);" class="nav-link icon"
							data-bs-toggle="sidebar-right" data-target=".sidebar-right">
							<i class="fe fe-align-right"></i>
						</a>
					</div>
					<!-- SIDE-MENU -->
					<div class="dropdown d-flex profile-1">
						<a data-bs-toggle="dropdown" class="nav-link leading-none d-flex">
							<img src="{{ Auth::user()->photo ? asset(Auth::user()->photo) : asset('users.png')  }}" alt="profile-user"
							class="avatar  profile-user brround cover-image">
						</a>
						<div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
							<div class="drop-heading">
								<div class="text-center">
									<h5 class="text-dark mb-0 fs-14 fw-semibold">{{ Auth::user()->name ?? 'Guest' }}</h5>
									<small class="text-muted">{{ Auth::user()->role->role ?? 'User' }}</small>
								</div>
							</div>
							<div class="dropdown-divider m-0"></div>
							<a class="dropdown-item" href="{{ route('users.profile', Auth::user()->user_id) }}" >
								<i class="dropdown-icon fe fe-user"></i> Profile
							</a>
							<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
								@csrf
							</form>

							<a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
								<i class="dropdown-icon fe fe-alert-circle"></i> Sign out
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>

<!-- <div style="background-color:white">
	<div class="container p-3">
		<b class="pl-2">Select Sites :</b>
		<select class="form-control select pl-2 select-branch" >
			<option value="all" {{ session('selected_branch') == 'all' ? 'selected' : '' }}>All Sites</option>
			@foreach ($sites as $site)
			<option value="{{ $site->site_id }}" {{ session('selected_branch') == $site->site_id ? 'selected' : '' }}>
				{{ $site->site_name }}
			</option>
			@endforeach
		</select>
	</div>
</div> -->
