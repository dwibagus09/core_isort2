
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                    <h1 class="page-title">File Manager Details</h1>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">File Manager</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">File Manager Details</li>
                                    </ol>
                                </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW-1 OPEN -->
                            <div class="row row-sm">
                                <div class="col-xl-8 col-lg-12 col-md-12">
                                    <div class="card custom-card overflow-hidden">
                                        <div class="card-body p-3">
                                            <a href="javascript:void(0);"><img src="{{asset('build/assets/images/media/35.jpg')}}" alt="img" class="br-5 w-100"></a>
                                        </div>
                                    </div>
                                    <div class="card custom-card">
                                        <div class="card-header border-bottom-0">
                                            Related Files
                                        </div>
                                        <div class="card-body pt-0 h-100">
                                            <div class="owl-carousel owl-carousel-icons2">
                                                <div class="item">
                                                    <div class="card overflow-hidden border p-0 mb-0 bg-white">
                                                        <a href="{{url('filemanager-details')}}"><img src="{{asset('build/assets/images/media/29.jpg')}}" alt="img" height="124" class="w-100"></a>
                                                        <div class="card-footer">
                                                            <div class="d-flex">
                                                                <div class="">
                                                                    <h5 class="mb-0 fw-semibold text-break">image2.jpg</h5>
                                                                </div>
                                                                <div class="ms-auto my-auto">
                                                                    <span class="text-muted mb-0">66 KB</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="card overflow-hidden border p-0 mb-0 bg-white">
                                                        <a href="{{url('filemanager-details')}}" class="mx-auto my-3"><img src="{{asset('build/assets/images/pngs/20.png')}}" alt="img"></a>
                                                        <div class="card-footer">
                                                            <div class="d-flex">
                                                                <div class="">
                                                                    <h5 class="mb-0 fw-semibold text-break">file.pdf</h5>
                                                                </div>
                                                                <div class="ms-auto my-auto">
                                                                    <span class="text-muted mb-0">32 KB</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="card overflow-hidden border p-0 mb-0 bg-white">
                                                        <a href="{{url('filemanager-details')}}"><img src="{{asset('build/assets/images/media/33.jpg')}}" alt="img" height="124" class="w-100"></a>
                                                        <div class="card-footer">
                                                            <div class="d-flex">
                                                                <div class="">
                                                                    <h5 class="mb-0 fw-semibold text-break">image1.jpg</h5>
                                                                </div>
                                                                <div class="ms-auto my-auto">
                                                                    <span class="text-muted mb-0">76 KB</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="card overflow-hidden border p-0 mb-0 bg-white">
                                                        <a href="{{url('filemanager-details')}}" class="mx-auto my-3"><img src="{{asset('build/assets/images/pngs/16.png')}}" alt="img"></a>
                                                        <div class="card-footer">
                                                            <div class="d-flex">
                                                                <div class="">
                                                                    <h5 class="mb-0 fw-semibold text-break">excel.xls</h5>
                                                                </div>
                                                                <div class="ms-auto my-auto">
                                                                    <span class="text-muted mb-0">34 KB</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="card overflow-hidden border p-0 mb-0 bg-white">
                                                        <a href="{{url('filemanager-details')}}"><img src="{{asset('build/assets/images/media/32.jpg')}}" alt="img" height="124" class="w-100"></a>
                                                        <div class="card-footer">
                                                            <div class="d-flex">
                                                                <div class="">
                                                                    <h5 class="mb-0 fw-semibold text-break">nature.jpg</h5>
                                                                </div>
                                                                <div class="ms-auto my-auto">
                                                                    <span class="text-muted mb-0">66 KB</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="card overflow-hidden border p-0 mb-0 bg-white">
                                                        <a href="{{url('filemanager-details')}}" class="mx-auto my-3"><img src="{{asset('build/assets/images/pngs/21.png')}}" alt="img"></a>
                                                        <div class="card-footer">
                                                            <div class="d-flex">
                                                                <div class="">
                                                                    <h5 class="mb-0 fw-semibold text-break">demo.ppt</h5>
                                                                </div>
                                                                <div class="ms-auto my-auto">
                                                                    <span class="text-muted mb-0">67 KB</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="card overflow-hidden border p-0 mb-0 bg-white">
                                                        <a href="{{url('filemanager-details')}}"><img src="{{asset('build/assets/images/media/28.jpg')}}" alt="img" height="124" class="w-100"></a>
                                                        <div class="card-footer">
                                                            <div class="d-flex">
                                                                <div class="d-flex">
                                                                    <h5 class="mb-0 fw-semibold text-break">image1.jpg</h5>
                                                                </div>
                                                                <div class="ms-auto my-auto">
                                                                    <span class="text-muted mb-0">76 KB</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-12 col-md-12">
                                    <div class="card custom-card">
                                        <div class="card-body">
                                            <h5 class="mb-3">File details</h5>
                                            <div class="">
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="table-responsive">
                                                            <table class="table mb-0 table-bordered text-nowrap">
                                                                <tbody>
                                                                    <tr>
                                                                        <th>File Name</th>
                                                                        <td>image.jpg</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Uploaded</th>
                                                                        <td>10-10-2021</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Size</th>
                                                                        <td>25 MB</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Dimensions</th>
                                                                        <td>1000 x 350</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Resolution Unit</th>
                                                                        <td>1</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>File Type</th>
                                                                        <td>jpg</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="mt-5 text-center">
                                                            <button type="button" class="btn btn-icon  btn-info-light me-2 br-5"><i class="fe fe-edit"></i></button>
                                                            <button type="button" class="btn btn-icon  btn-danger-light me-2 br-5"><i class="fe fe-trash"></i></button>
                                                            <button type="button" class="btn btn-icon  btn-success-light me-2 br-5"><i class="fe fe-download fs-14"></i></button>
                                                            <button type="button" class="btn btn-icon  btn-warning-light br-5"><i class="fe fe-share-2 fs-14"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card custom-card">
                                        <div class="card-body pb-0">
                                            <h5 class="mb-3">Recent Files</h5>
                                            <ul id="lightgallery" class="list-unstyled row">
                                                <li class="col-xs-6 col-sm-4 col-md-4 col-xl-4 mb-5 border-bottom-0" data-responsive="{{asset('build/assets/images/media/27.jpg')}}" data-src="{{asset('build/assets/images/media/27.jpg')}}" data-sub-html="<h4>Gallery Image 1</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
                                                    <a href="javascript:void(0);">
                                                        <img class="img-responsive br-5" src="{{asset('build/assets/images/media/27.jpg')}}" alt="Thumb-1">
                                                    </a>
                                                </li>
                                                <li class="col-xs-6 col-sm-4 col-md-4 col-xl-4 mb-5 border-bottom-0" data-responsive="{{asset('build/assets/images/media/28.jpg')}}" data-src="{{asset('build/assets/images/media/28.jpg')}}" data-sub-html="<h4>Gallery Image 2</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
                                                    <a href="javascript:void(0);">
                                                        <img class="img-responsive br-5" src="{{asset('build/assets/images/media/28.jpg')}}" alt="Thumb-2">
                                                    </a>
                                                </li>
                                                <li class="col-xs-6 col-sm-4 col-md-4 col-xl-4 mb-5 border-bottom-0" data-responsive="{{asset('build/assets/images/media/29.jpg')}}" data-src="{{asset('build/assets/images/media/29.jpg')}}" data-sub-html="<h4>Gallery Image 3</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
                                                    <a href="javascript:void(0);">
                                                        <img class="img-responsive br-5" src="{{asset('build/assets/images/media/29.jpg')}}" alt="Thumb-1">
                                                    </a>
                                                </li>
                                                <li class="col-xs-6 col-sm-4 col-md-4 col-xl-4 mb-5 border-bottom-0" data-responsive="{{asset('build/assets/images/media/30.jpg')}}" data-src="{{asset('build/assets/images/media/30.jpg')}}" data-sub-html=" <h4>Gallery Image 4</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
                                                    <a href="javascript:void(0);">
                                                        <img class="img-responsive br-5" src="{{asset('build/assets/images/media/30.jpg')}}" alt="Thumb-2">
                                                    </a>
                                                </li>
                                                <li class="col-xs-6 col-sm-4 col-md-4 col-xl-4 mb-5 border-bottom-0" data-responsive="{{asset('build/assets/images/media/31.jpg')}}" data-src="{{asset('build/assets/images/media/31.jpg')}}" data-sub-html="<h4>Gallery Image 5</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
                                                    <a href="javascript:void(0);">
                                                        <img class="img-responsive br-5" src="{{asset('build/assets/images/media/31.jpg')}}" alt="Thumb-1">
                                                    </a>
                                                </li>
                                                <li class="col-xs-6 col-sm-4 col-md-4 col-xl-4 mb-5 border-bottom-0" data-responsive="{{asset('build/assets/images/media/32.jpg')}}" data-src="{{asset('build/assets/images/media/32.jpg')}}" data-sub-html="<h4>Gallery Image 6</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
                                                    <a href="javascript:void(0);">
                                                        <img class="img-responsive br-5" src="{{asset('build/assets/images/media/32.jpg')}}" alt="Thumb-2">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-1 CLOSED -->

@endsection

@section('scripts')

        <!-- OWL CAROUSEL JS-->
        <script src="{{asset('build/assets/plugins/owl-carousel/owl.carousel.js')}}"></script>
		@vite('resources/assets/js/owl-carousel.js')

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
