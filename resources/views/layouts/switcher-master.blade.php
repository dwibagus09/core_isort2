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
	    <title>iSort CMMS</title>

		<!-- Favicon -->
		<link rel="icon" href="{{asset('build/assets/images/brand/isort_new_logo.png')}}" type="image/x-icon">

        <!-- BOOTSTRAP CSS -->
	    <link id="style" href="{{asset('build/assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" >
       
        <!-- APP CSS & APP SCSS -->
        @vite(['resources/css/app.css' , 'resources/sass/app.scss'])

        @yield('styles')
        
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

            <!-- Sidebar-right-->
            @include('layouts.components.sidebar-right')
            <!-- End Sidebar-right-->

            <!-- Country-selector modal-->
            @include('layouts.components.modal')
            <!-- End Country-selector modal-->

            <!-- Footer opened -->
			@include('layouts.components.footer')
            <!-- End Footer -->
            
            @yield('modals')

		</div>
        <!-- END PAGE-->

        <!-- SCRIPTS -->
        @include('layouts.components.switcher-scripts')
        
        <!-- APP JS-->
		@vite('resources/js/app.js')
        <!-- END SCRIPTS -->

	</body>
</html>
