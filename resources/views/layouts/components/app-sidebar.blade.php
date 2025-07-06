<div class="sticky">
	<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
	<div class="app-sidebar">
		<div class="side-header">
			<div style="display: flex; align-items: center;">
				<a class="header-brand1" href="{{url('index')}}">
					<img src="{{asset('build/assets/images/brand/dark_logo.png')}}" width="50" height="50" class="header-brand-img desktop-logo" alt="logo">
					<img src="{{asset('build/assets/images/brand/dark_logo.png')}}"  width="25" height="25" class="header-brand-img toggle-logo" alt="logo">
					<img src="{{asset('build/assets/images/brand/dark_logo.png')}}"  width="50" height="50" class="header-brand-img light-logo" alt="logo">
					<img src="{{asset('build/assets/images/brand/dark_logo.png')}}"  width="50" height="50" class="header-brand-img light-logo1" alt="logo">
				</a>
				<span style="color: #907246; font-size: 24px; font-weight: bold; letter-spacing: 2px;">iSort</span>
			</div>

			<!-- LOGO -->
		</div>
		<div class="main-sidemenu">
			<div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg"
				fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
				<path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
			</svg></div>
			<ul class="side-menu" style="margin-left:15px !important;">
				@php
				$menus = \App\Http\Controllers\MenuController::getMenu();
				$menusUsers = \App\Http\Controllers\MenuController::getMenuUsers();
				@endphp

				<li class="slide">
					<a class="side-menu__item" href="{{url('index')}}" style="padding:8px 5px !important">
						<i class="side-menu__icon fe fe-airplay"></i>
						<span class="side-menu__label">Dashboards</span>
					</a>
				</li>

				@foreach($menusUsers as $menu)
                    <li class="slide">
                        <a class="side-menu__item" href="{{ $menu['url'] }}" style="padding:8px 5px !important">
                            <i class="side-menu__icon fe fe-{{ $menu['icon_name'] ?? 'circle' }}"></i>
                            <span class="side-menu__label">{{ $menu['menu_name'] }}</span>
                        </a>
                    </li>
                @endforeach

				<!--	<li class="slide">-->
				<!--	<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-settings"></i><span-->
				<!--		class="side-menu__label">Administrator</span>-->
				<!--		<i-->
				<!--		class="angle fe fe-chevron-right"></i>-->
				<!--	</a>-->

				<!--	<ul class="slide-menu">-->
				<!--	    @foreach($menus as $menu)-->
				<!--                               <li class="slide">-->
				<!--                                   <a href="{{ $menu['menu_name'] }}" class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">-->
				<!--                                       <i class="side-menu__icon {{ $menu['icon_name'] }}"></i> -->
				<!--                                       <span class="side-menu__label">{{ $menu['menu_name'] }}</span>-->
				<!--                                           <i class="angle fe fe-chevron-right"></i>-->
				<!--                                   </a>-->
				<!--                                           <ul class="slide-menu">-->
				<!--                                               <li><a href="{{ route('area.list', ['department_id' => $menu['id']]) }}" class="slide-item">Area</a></li>-->
				<!--                                           </ul>-->
				<!--                                           <ul class="slide-menu">-->
				<!--                                               <li><a href="{{ route('location.index', ['department_id' => $menu['id']]) }}" class="slide-item">Location</a></li>-->
				<!--                                           </ul>-->
				<!--                                           <ul class="slide-menu">-->
				<!--                                               <li><a href="{{ route('incident.index', ['department_id' => $menu['id']]) }}" class="slide-item">Incident</a></li>-->
				<!--                                           </ul>-->
				<!--                                           <ul class="slide-menu">-->
				<!--                                               <li><a href="{{ route('modus.index', ['department_id' => $menu['id']]) }}" class="slide-item">Modus</a></li>-->
				<!--                                           </ul>-->
				<!--                               </li>-->
				<!--                           @endforeach    -->
				<!--		<li class="panel sidetab-menu">-->
				<!--			<div class="panel-body tabs-menu-body p-0 border-0">-->
				<!--				<div class="tab-content">-->
				<!--					<div class="tab-pane active" id="side5">-->
				<!--						<ul class="sidemenu-list">-->
				<!--						      <li>-->
				<!--                   										<a class="slide-item" href="{{url('department/view')}}"><span-->
				<!--                   												class="side-menu__label">Department</span></a>-->
				<!--                   									</li>-->
				<!--                   									<li>-->
				<!--                   										<a class="slide-item" href="{{url('user/view')}}"><span-->
				<!--                   												class="side-menu__label">Users</span></a>-->
				<!--                   									</li>-->
				<!--							<li><a href="{{url('/role/index')}}" class="slide-item">Role</a></li>-->
				<!--							<li><a href="{{url('/permission/index')}}" class="slide-item">Permission</a></li>-->
				<!--							<li><a href="{{url('/admin/kaizen/index')}}" class="slide-item">Kaizen</a></li>-->
				<!--							<li><a href="{{url('/admin/kaizen/wo/index')}}" class="slide-item">Work Order</a></li>-->
				<!--							<li><a href="{{url('/admin/digitalchecklist/template/index')}}" class="slide-item">Digital Checklist</a></li>-->


				<!--	                    </ul>-->


				<!--					</div>-->
				<!--				</div>-->
				<!--			</div>-->
				<!--		</li>-->
				<!--	</ul>-->
				<!--</li>-->
				<li class="slide">
					<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);" style="padding:8px 5px !important">
						<i class="side-menu__icon fe fe-settings"></i>
						<span class="side-menu__label">Administrator</span>
						<i class="angle fe fe-chevron-right"></i>
					</a>

					<ul class="slide-menu">
						<!-- Menu Dinamis -->
						@foreach($menus as $menu)
						<li class="sub-slide">
							<a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0)">
								<!--<i class="sub-side-menu__icon {{ $menu['icon_name'] }}"></i>-->
								<span class="sub-side-menu__label">{{ $menu['menu_name'] }}</span>
								<i class="angle fe fe-chevron-right"></i>
							</a>
							<ul class="sub-slide-menu">
								<!-- Submenu Area -->
								<li>
									<a href="{{ route('area.list', ['department_id' => $menu['id']]) }}" class="slide-item">Area</a>
								</li>

								<!-- Submenu Location -->
								<li>
									<a href="{{ route('location.index', ['department_id' => $menu['id']]) }}" class="slide-item">Location</a>
								</li>

								<li>
									<a href="{{ route('typekaizen.list', ['department_id' => $menu['id']]) }}" class="slide-item">Kaizen Type</a>
								</li>

								<!-- Submenu Incident -->
								<li>
									<a href="{{ route('incident.index', ['department_id' => $menu['id']]) }}" class="slide-item">Item</a>
								</li>

								<!-- Submenu Modus -->
								<li>
									<a href="{{ route('modus.index', ['department_id' => $menu['id']]) }}" class="slide-item">Sub Item</a>
								</li>
							</ul>
						</li>
						@endforeach

						<!-- Menu Statis -->
						<li class="panel sidetab-menu">
							<div class="panel-body tabs-menu-body p-0 border-0">
								<div class="tab-content">
									<div class="tab-pane active" id="side5">
										<ul class="sidemenu-list">
											<li>
												<a class="slide-item" href="{{url('department/view')}}">
													<span class="side-menu__label">Department</span>
												</a>
											</li>
											<li>
												<a class="slide-item" href="{{url('user/view')}}">
													<span class="side-menu__label">Users</span>
												</a>
											</li>
											<li><a href="{{url('/role/index')}}" class="slide-item">Role</a></li>
											<li><a href="{{url('/permission/index')}}" class="slide-item">Permission</a></li>
											<li><a href="{{url('/admin/kaizen/index')}}" class="slide-item">Kaizen</a></li>
											<li><a href="{{url('/admin/kaizen/wo/index')}}" class="slide-item">Work Order</a></li>
											<li><a href="{{url('/admin/digitalchecklist/template/index')}}" class="slide-item">Digital Checklist</a></li>
											<li><a href="{{url('/admin/othersetting/index')}}" class="slide-item">Other Settings</a></li>
											@if(!session()->has('selected_branch') || session('selected_branch') == 'all')
												<li><a href="{{url('/admin/sites/index')}}" class="slide-item">Sites</a></li>
											@endif
										</ul>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</li>

			</ul>
			<div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
				width="24" height="24" viewBox="0 0 24 24">
				<path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
			</svg></div>
		</div>
	</div>
</div>
