
<footer class="footer">
	<div class="container">
		@php
		$menus = \App\Http\Controllers\MenuController::getMenu();
		$statusCamera = \App\Http\Controllers\OtherSettingsController::getCameraSetting();
		@endphp
		@if(session('selected_branch') && session('selected_branch') != 'all')
		<div class="mobile-tab-view">
			<button class="btn btn-md btn-primary btn-block btn-submit-kaizen" data-status="{{ $statusCamera }}">
				<img src="{{asset('build/assets/images/isort_logo_white.png')}}" height="30px"  alt="logo">
			</button>
			<!-- <input type="file" id="fileInputKaizen" style="display: none;" accept="image/*"> -->
		</div>
		<div class="desktop-view">
			<div class="row align-items-center flex-row-reverse">
				<div class="col-lg-12 col-sm-12  text-center ">
					Copyright © 2024 <a href="javascript:void(0);">iSort Teknologi Indonesia</a>.
				</div>
			</div>
		</div>
		@else
		<div>
			<div class="row align-items-center flex-row-reverse">
				<div class="col-lg-12 col-sm-12  text-center ">
					Copyright © 2024 <a href="javascript:void(0);">iSort Teknologi Indonesia</a>.
				</div>
			</div>
		</div>
		@endif
		</div>
	</footer>
