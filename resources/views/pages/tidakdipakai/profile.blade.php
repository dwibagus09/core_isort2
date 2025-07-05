
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
															<img class="" src="{{asset('build/assets/images/users/10.jpg')}}" alt="img">
														</div>
														<div class="user-wrap">
															<h4>Elizabeth Dyer</h4>
															<h6 class="text-muted mb-3">Member Since: November 2017</h6>
															<a href="javascript:void(0);" class="btn btn-primary me-2 mt-1 mb-1"><i class="fa fa-rss"></i> Follow</a>
															<a href="{{url('email')}}" class="btn btn-secondary mt-1 mb-1"><i class="fa fa-envelope"></i> E-mail</a>
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
														<li><a href="#tab-61" data-bs-toggle="tab" class="">Friends</a></li>
														<li><a href="#tab-71" data-bs-toggle="tab" class="">Gallery</a></li>
														<li><a href="#tab-81" data-bs-toggle="tab" class="">Followers</a></li>
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
																<div class="table-responsive ">
																	<table class="table row table-borderless">
																		<tbody class="col-lg-12 col-xl-6 p-0">
																			<tr>
																				<td><strong>Full Name :</strong> Elizabeth Dyer</td>
																			</tr>
																			<tr>
																				<td><strong>Location :</strong> USA</td>
																			</tr>
																			<tr>
																				<td><strong>Languages :</strong> English, German, Spanish.</td>
																			</tr>
																		</tbody>
																		<tbody class="col-lg-12 col-xl-6 p-0">
																			<tr>
																				<td><strong>Website :</strong> abcdz.com</td>
																			</tr>
																			<tr>
																				<td><strong>Email :</strong> georgemestayer@abcdz.com</td>
																			</tr>
																			<tr>
																				<td><strong>Phone :</strong> +125 254 3562 </td>
																			</tr>
																		</tbody>
																	</table>
																</div>
																<div class="row profie-img">
																	<div class="col-md-12">
																		<div class="media-heading">
																			<h5><strong>Biography</strong></h5>
																		</div>
																		<p>
																			Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus</p>
																		<p class="mb-0">because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter but because those who do not know how to pursue consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure.</p>
																	</div>
																</div>
															</div>
														</div>
												    </div>
												</div>
												<div class="tab-pane" id="tab-61">
													<ul class="widget-users row">
														<li class="col-xl-3 col-md-6 col-sm-12 col-12">
															<div class="card">
																<div class="card-body text-center">
																	<span class="avatar avatar-xxl brround cover-image" data-bs-image-src="{{asset('build/assets/images/users/15.jpg')}}"></span>
																	<h4 class="h4 mb-0 mt-4 fw-semibold">James Thomas</h4>
																	<p class="card-text">Web designer</p>
																	<div class="user-social-detail mt-4">
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-primary-transparent"><i class="fa fa-facebook text-primary" aria-hidden="true"></i></a>
																		</div>
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-danger-transparent"><i class="fa fa-google-plus text-danger" aria-hidden="true"></i></a>
																		</div>
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-info-transparent"><i class="fa fa-twitter text-info" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</div>
															</div>
														</li>
														<li class="col-xl-3 col-md-6 col-sm-12 col-12">
															<div class="card">
																<div class="card-body text-center">
																	<span class="avatar avatar-xxl brround cover-image" data-bs-image-src="{{asset('build/assets/images/users/9.jpg')}}"></span>
																	<h4 class="h4 mb-0 mt-4 fw-semibold">George Clooney</h4>
																	<p class="card-text">Web designer</p>
																	<div class="user-social-detail mt-4">
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-primary-transparent"><i class="fa fa-facebook text-primary" aria-hidden="true"></i></a>
																		</div>
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-danger-transparent"><i class="fa fa-google-plus text-danger" aria-hidden="true"></i></a>
																		</div>
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-info-transparent"><i class="fa fa-twitter text-info" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</div>
															</div>
														</li>
														<li class="col-xl-3 col-md-6 col-sm-12 col-12">
															<div class="card">
																<div class="card-body text-center">
																	<span class="avatar avatar-xxl brround cover-image" data-bs-image-src="{{asset('build/assets/images/users/16.jpg')}}"></span>
																	<h4 class="h4 mb-0 mt-4 fw-semibold">Robert Downey Jr.</h4>
																	<p class="card-text">Web designer</p>
																	<div class="user-social-detail mt-4">
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-primary-transparent"><i class="fa fa-facebook text-primary" aria-hidden="true"></i></a>
																		</div>
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-danger-transparent"><i class="fa fa-google-plus text-danger" aria-hidden="true"></i></a>
																		</div>
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-info-transparent"><i class="fa fa-twitter text-info" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</div>
															</div>
														</li>
														<li class="col-xl-3 col-md-6 col-sm-12 col-12">
															<div class="card">
																<div class="card-body text-center">
																	<span class="avatar avatar-xxl brround cover-image" data-bs-image-src="{{asset('build/assets/images/users/12.jpg')}}"></span>
																	<h4 class="h4 mb-0 mt-4 fw-semibold">Emma Watson</h4>
																	<p class="card-text">Web designer</p>
																	<div class="user-social-detail mt-4">
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-primary-transparent"><i class="fa fa-facebook text-primary" aria-hidden="true"></i></a>
																		</div>
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-danger-transparent"><i class="fa fa-google-plus text-danger" aria-hidden="true"></i></a>
																		</div>
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-info-transparent"><i class="fa fa-twitter text-info" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</div>
															</div>
														</li>
														<li class="col-xl-3 col-md-6 col-sm-12 col-12">
															<div class="card">
																<div class="card-body text-center">
																	<span class="avatar avatar-xxl brround cover-image" data-bs-image-src="{{asset('build/assets/images/users/4.jpg')}}"></span>
																	<h4 class="h4 mb-0 mt-4 fw-semibold">Mila Kunis</h4>
																	<p class="card-text">Web designer</p>
																	<div class="user-social-detail mt-4">
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-primary-transparent"><i class="fa fa-facebook text-primary" aria-hidden="true"></i></a>
																		</div>
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-danger-transparent"><i class="fa fa-google-plus text-danger" aria-hidden="true"></i></a>
																		</div>
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-info-transparent"><i class="fa fa-twitter text-info" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</div>
															</div>
														</li>
														<li class="col-xl-3 col-md-6 col-sm-12 col-12">
															<div class="card">
																<div class="card-body text-center">
																	<span class="avatar avatar-xxl brround cover-image" data-bs-image-src="{{asset('build/assets/images/users/8.jpg')}}"></span>
																	<h4 class="h4 mb-0 mt-4 fw-semibold">kathrine thio</h4>
																	<p class="card-text">Web designer</p>
																	<div class="user-social-detail mt-4">
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-primary-transparent"><i class="fa fa-facebook text-primary" aria-hidden="true"></i></a>
																		</div>
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-danger-transparent"><i class="fa fa-google-plus text-danger" aria-hidden="true"></i></a>
																		</div>
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-info-transparent"><i class="fa fa-twitter text-info" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</div>
															</div>
														</li>
														<li class="col-xl-3 col-md-6 col-sm-12 col-12">
															<div class="card">
																<div class="card-body text-center">
																	<span class="avatar avatar-xxl brround cover-image" data-bs-image-src="{{asset('build/assets/images/users/20.jpg')}}"></span>
																	<h4 class="h4 mb-0 mt-4 fw-semibold">Malari Koshy</h4>
																	<p class="card-text">Web designer</p>
																	<div class="user-social-detail mt-4">
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-primary-transparent"><i class="fa fa-facebook text-primary" aria-hidden="true"></i></a>
																		</div>
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-danger-transparent"><i class="fa fa-google-plus text-danger" aria-hidden="true"></i></a>
																		</div>
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-info-transparent"><i class="fa fa-twitter text-info" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</div>
															</div>
														</li>
														<li class="col-xl-3 col-md-6 col-sm-12 col-12">
															<div class="card">
																<div class="card-body text-center">
																	<span class="avatar avatar-xxl brround cover-image" data-bs-image-src="{{asset('build/assets/images/users/6.jpg')}}"></span>
																	<h4 class="h4 mb-0 mt-4 fw-semibold">Ryan Gossling</h4>
																	<p class="card-text">Web designer</p>
																	<div class="user-social-detail mt-4">
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-primary-transparent"><i class="fa fa-facebook text-primary" aria-hidden="true"></i></a>
																		</div>
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-danger-transparent"><i class="fa fa-google-plus text-danger" aria-hidden="true"></i></a>
																		</div>
																		<div>
																			<a href="javascript:void(0);" class="social-icons bg-info-transparent"><i class="fa fa-twitter text-info" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</div>
															</div>
														</li>
													</ul>
												</div>
												<div class="tab-pane" id="tab-71">
													<ul id="lightgallery" class="list-unstyled row">
														<li class="col-xs-6 col-sm-4 col-md-4 col-xl-3 mb-5 border-bottom-0" data-responsive="{{asset('build/assets/images/media/1.jpg')}}" data-src="{{asset('build/assets/images/media/1.jpg')}}" data-sub-html="<h4>Gallery Image 1</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
															<a href="">
																<img class="img-responsive br-5" src="{{asset('build/assets/images/media/1.jpg')}}" alt="Thumb-1">
															</a>
														</li>
														<li class="col-xs-6 col-sm-4 col-md-4 col-xl-3 mb-5 border-bottom-0" data-responsive="{{asset('build/assets/images/media/2.jpg')}}" data-src="{{asset('build/assets/images/media/2.jpg')}}" data-sub-html="<h4>Gallery Image 2</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
															<a href="">
																<img class="img-responsive br-5" src="{{asset('build/assets/images/media/2.jpg')}}" alt="Thumb-2">
															</a>
														</li>
														<li class="col-xs-6 col-sm-4 col-md-4 col-xl-3 mb-5 border-bottom-0" data-responsive="{{asset('build/assets/images/media/3.jpg')}}" data-src="{{asset('build/assets/images/media/3.jpg')}}" data-sub-html="<h4>Gallery Image 3</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
															<a href="">
																<img class="img-responsive br-5" src="{{asset('build/assets/images/media/3.jpg')}}" alt="Thumb-1">
															</a>
														</li>
														<li class="col-xs-6 col-sm-4 col-md-4 col-xl-3 mb-5 border-bottom-0" data-responsive="{{asset('build/assets/images/media/4.jpg')}}" data-src="{{asset('build/assets/images/media/4.jpg')}}" data-sub-html=" <h4>Gallery Image 4</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
															<a href="">
																<img class="img-responsive br-5" src="{{asset('build/assets/images/media/4.jpg')}}" alt="Thumb-2">
															</a>
														</li>
														<li class="col-xs-6 col-sm-4 col-md-4 col-xl-3 mb-5 border-bottom-0" data-responsive="{{asset('build/assets/images/media/5.jpg')}}" data-src="{{asset('build/assets/images/media/5.jpg')}}" data-sub-html="<h4>Gallery Image 5</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
															<a href="">
																<img class="img-responsive br-5" src="{{asset('build/assets/images/media/5.jpg')}}" alt="Thumb-1">
															</a>
														</li>
														<li class="col-xs-6 col-sm-4 col-md-4 col-xl-3 mb-5 border-bottom-0" data-responsive="{{asset('build/assets/images/media/6.jpg')}}" data-src="{{asset('build/assets/images/media/6.jpg')}}" data-sub-html="<h4>Gallery Image 6</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
															<a href="">
																<img class="img-responsive br-5" src="{{asset('build/assets/images/media/6.jpg')}}" alt="Thumb-2">
															</a>
														</li>
														<li class="col-xs-6 col-sm-4 col-md-4 col-xl-3 mb-5 border-bottom-0" data-responsive="{{asset('build/assets/images/media/7.jpg')}}" data-src="{{asset('build/assets/images/media/7.jpg')}}" data-sub-html="<h4>Gallery Image 7</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
															<a href="">
																<img class="img-responsive br-5" src="{{asset('build/assets/images/media/7.jpg')}}" alt="Thumb-1">
															</a>
														</li>
														<li class="col-xs-6 col-sm-4 col-md-4 col-xl-3 mb-5 border-bottom-0" data-responsive="{{asset('build/assets/images/media/8.jpg')}}" data-src="{{asset('build/assets/images/media/8.jpg')}}" data-sub-html="<h4>Gallery Image 8</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
															<a href="">
																<img class="img-responsive br-5" src="{{asset('build/assets/images/media/8.jpg')}}" alt="Thumb-2">
															</a>
														</li>
													</ul>
												</div>
												<div class="tab-pane" id="tab-81">
													<div class="row">
														<div class=" col-lg-6 col-md-12">
															<div class="card borderover-flow-hidden">
																<div class="media card-body media-xs overflow-visible d-sm-flex d-block m-0">
																	<span class="avatar cover-image avatar-md brround bg-pink me-3">LQ</span>
																	<div class="media-body valign-middle my-auto">
																		<a href="" class="fw-semibold text-dark">Lillian Quinn</a>
																		<p class="text-muted m-0">lilliangore</p>
																	</div>
																	<div class="media-body valign-middle text-sm-end overflow-visible my-auto">
																		<button class="btn btn-secondary" type="button">Follow</button>
																	</div>
																</div>
															</div>
														</div>
														<div class=" col-lg-6 col-md-12">
															<div class="card borderover-flow-hidden">
																<div class="media card-body media-xs overflow-visible d-sm-flex d-block m-0">
																	<span class="avatar cover-image avatar-md brround me-3 my-auto bg-primary">IH</span>
																	<div class="media-body valign-middle my-auto">
																		<a href="" class="fw-semibold text-dark">Irene Harris</a>
																		<p class="text-muted m-0">ireneharris@gmail.com</p>
																	</div>
																	<div class="media-body valign-middle text-sm-end overflow-visible my-auto">
																		<button class="btn btn-success" type="button">Follow</button>
																	</div>
																</div>
															</div>
														</div>
														<div class=" col-lg-6 col-md-12">
															<div class="card borderover-flow-hidden">
																<div class="media card-body media-xs overflow-visible d-sm-flex d-block m-0">
																	<img class="avatar brround avatar-md me-3" src="{{asset('build/assets/images/users/18.jpg')}}" alt="avatar-img">
																	<div class="media-body valign-middle my-auto">
																		<a href="" class=" fw-semibold text-dark">John Paige</a>
																		<p class="text-muted m-0">johan@gmail.com</p>
																	</div>
																	<div class="media-body valign-middle text-sm-end overflow-visible my-auto">
																		<button class="btn btn-primary" type="button">Follow</button>
																	</div>
																</div>
															</div>
														</div>
														<div class=" col-lg-6 col-md-12">
															<div class="card borderover-flow-hidden">
																<div class="media card-body media-xs overflow-visible d-sm-flex d-block m-0">
																	<img class="avatar brround avatar-md me-3" src="{{asset('build/assets/images/users/2.jpg')}}" alt="avatar-img">
																	<div class="media-body valign-middle my-auto">
																		<a href="" class="text-dark fw-semibold">Harry Fisher</a>
																		<p class="text-muted m-0 mb-0">harryuqt</p>
																	</div>
																	<div class="media-body valign-middle text-sm-end overflow-visible my-auto">
																		<button class="btn btn-danger" type="button">Follow</button>
																	</div>
																</div>
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

@endsection
