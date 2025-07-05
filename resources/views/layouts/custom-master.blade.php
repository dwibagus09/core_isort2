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


		<!-- TITLE -->
		<title>iSort Administrator</title>

		<!-- Favicon -->
		<link rel="icon" href="{{asset('build/assets/images/brand/isort_new_logo.png')}}" type="image/x-icon">

        <!-- BOOTSTRAP CSS -->
	    <link id="style" href="{{asset('build/assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" >

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

        <link href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.css" rel="stylesheet">

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
            /* CSS untuk border berdasarkan status */
            .img-status {
                border-width: 3px;
                border-style: solid;
            }

            .img-status.red {
                border-color: red; /* Belum dikerjakan */
            }

            .img-status.yellow {
                border-color: yellow; /* Sedang dikerjakan */
            }

            .img-status.green {
                border-color: green; /* Selesai */
            }
        </style>

        <script>
        $(document).ready(function() {
            $('#select-branch').change(function() {
                var selectedBranchId = $(this).val();

                // Kirim permintaan ke controller untuk menyimpan nilai cabang yang dipilih
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
                        // Lakukan operasi lain jika diperlukan setelah nilai cabang disimpan
                    },
                    error: function(error) {
                        console.error('Gagal menyimpan pilihan cabang');
                        // Lakukan penanganan kesalahan jika diperlukan
                    }
                });
            });
        });
    </script>

	</head>

	<body class="login-img">

		<!-- GLOBAL-LOADER -->
		<div id="global-loader">
			<img src="{{asset('build/assets/images/svgs/loader.svg')}}" class="loader-img" alt="Loader">
		</div>
		<!-- GLOBAL-LOADER -->

		<!-- PAGE -->
		<div class="page bg-img">
        	@yield('content')

        	</div>

		</div>

		<!-- JQUERY JS -->
		<script src="{{asset('build/assets/plugins/jquery/jquery.min.js')}}"></script>

		<!-- BOOTSTRAP JS -->
		<script src="{{asset('build/assets/plugins/bootstrap/js/popper.min.js')}}"></script>
		<script src="{{asset('build/assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

		@yield('scripts')

        <!-- APP JS-->
		@vite('resources/js/app.js')
        <!-- END SCRIPTS -->

	</body>
</html>
