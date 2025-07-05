<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>

	<!-- META DATA -->
	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="CMMS iSort Indonesia">
	<meta name="author" content="iSort Indonesia">
	<meta name="keywords" content="iSort">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<meta property="og:title" content="Open Kaizen" />
    <meta property="og:description" content="{{ $keteranganKaizen ?? 'Kaizen' }}">
    <meta property="og:image" content="{{ isset($pictureKaizen) ? $pictureKaizen . '?v=' . time() : 'https://cmms.hanzel.id/build/assets/images/brand/isort_new_logo.png?v=' . time() }}">
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />


	<!-- TITLE -->
	<title>iSort Administrator</title>

	<!-- Favicon -->
	<link rel="icon" href="{{asset('build/assets/images/brand/isort_new_logo.png')}}" type="image/x-icon">

	<!-- BOOTSTRAP CSS -->
	<link id="style" href="{{asset('build/assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" >

	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
	<!--<link href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.css" rel="stylesheet">-->
	<link href="https://cdn.jsdelivr.net/npm/feather-icons-css@1.2.0/css/feather.min.css" rel="stylesheet">


	<!-- Tambahkan ini di head atau sebelum script yang menggunakan Swal -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<!-- DataTables CSS -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

	<!-- DataTables JS -->
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

	<!-- APP CSS & APP SCSS -->
	@vite(['resources/css/app.css' , 'resources/sass/app.scss'])

	@yield('styles')

	<style>
	.mobile-tab-view{
		display: none;
	}

	.desktop-view{
		display: block;
	}

	@media screen and (max-width: 1024px) {
  	.mobile-tab-view {
	    display: block;
	  }
		.desktop-view{
			display: none;
		}
	}

	/* Background putih untuk menu aktif */
	.side-menu .slide.active {
		background-color: white;
		border-radius: 5px; /* Optional: untuk sudut yang melengkung */
	}

	/* Warna teks pada menu aktif */
	.side-menu .slide.active .side-menu__item {
		color: #907246; /* Warna teks saat aktif */
	}

	/* Hover effect untuk menu */
	.side-menu .slide:hover {
		background-color: rgba(255, 255, 255, 0.2); /* Efek hover */
		border-radius: 5px;
	}


	/* Efek blur untuk konten utama */
	.blur-content {
		filter: blur(5px);
		pointer-events: none;
		user-select: none;
	}

	/* Pastikan header dan SEMUA komponennya tidak kena blur */
	.app-header.header.sticky,
	.app-header.header.sticky *,
	.header-right-icons,
	.header-right-icons *,
	.navbar-responsive-toggler,
	.navbar-responsive-toggler *,
	#select-branch,
	.profile-1,
	.profile-1 * {
		filter: none !important;
		pointer-events: auto !important;
		user-select: auto !important;
	}

	/* Modal backdrop */
	.modal-backdrop {
		opacity: 0.5 !important;
		z-index: 1040 !important;
	}

	#siteInfoModal {
		z-index: 1060 !important;
	}

	/* Style untuk pesan informasi */
	.alert-important {
		background-color: #f8f9fa;
		border-left: 4px solid #5e72e4;
		padding: 1rem;
		margin-bottom: 0;
	}

	/* Style untuk placeholder saat belum pilih site */
	.empty-state {
		text-align: center;
		padding: 2rem;
		color: #6c757d;
	}

	.empty-state i {
		font-size: 3rem;
		margin-bottom: 1rem;
		color: #adb5bd;
	}

	.slide-menu{
		padding-inline-start:10px;
	}

	</style>


	<script>
	$(document).ready(function() {
		$('#select-branch').change(function() {
			var selectedBranchId = $(this).val();
			$.ajax({
				url: "{{ route('save-selected-branch') }}",
				type: 'POST',
				data: {
					_token: '{{ csrf_token() }}',
					selected_branch: selectedBranchId
				},
				success: function(response) {
					console.log('Pilihan cabang berhasil disimpan');
					location.reload();
				},
				error: function(error) {
					console.error('Gagal menyimpan pilihan cabang');
				}
			});
		});
	});
	</script>


</head>

<body class="app ltr sidebar-mini light-mode">

	<!-- GLOBAL-LOADER -->
	<div id="global-loader">
		<img src="{{asset('build/assets/images/svgs/loader.svg')}}" class="loader-img" alt="Loader">
	</div>
	<!-- GLOBAL-LOADER -->

	<!-- PAGE -->
	<div class="page">

		<div class="page-main">

			<!-- App-Header -->
			@include('layouts.components.app-header')
			<!-- End App-Header -->

			<!--App-Sidebar-->
			@include('layouts.components.app-sidebar')
			<!-- End App-Sidebar-->

			<!--app-content open-->
			<div class="app-content main-content">
				<div class="side-app">
					<div class="main-container">

						@yield('content')

					</div>
				</div>
				<!-- Container closed -->
			</div>
			<!-- main-content closed -->

		</div>

		<!-- Sidebar-right -->
		@include('layouts.components.sidebar-right')
		<!-- End Sidebar-right -->

		<!-- Country-selector modal -->
		@include('layouts.components.modal')
		<!-- End Country-selector modal -->

		<!-- Footer opened -->
		@include('layouts.components.footer')
		<!-- End Footer -->

		@yield('modals')

	</div>
	<!-- END PAGE-->

	<!-- SCRIPTS -->
	@include('layouts.components.scripts')

	<!-- APP JS-->
	@vite('resources/js/app.js')
	<!-- END SCRIPTS -->

</body>
</html>
