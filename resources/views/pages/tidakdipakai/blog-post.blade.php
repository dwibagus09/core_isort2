
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Blog Post</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Pages</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Blog Post</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW-1 OPEN -->
                            <div class="row">
                                <div class="col-xl-8">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Add New Post</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-4 ms-0">
                                                <label class="col-md-3 form-label ps-0">Post Title :</label>
                                                <div class="">
                                                    <input type="text" class="form-control" placeholder="Typing.....">
                                                </div>
                                            </div>
                                            <div class="row mb-4 ms-0">
                                                <label class="col-md-3 form-label ps-0">Categories :</label>
                                                <div class="">
                                                    <select name="country" class="form-control form-select select2" data-bs-placeholder="Select Country">
                                                        <option value="br">Technology</option>
                                                        <option value="cz">Travel</option>
                                                        <option value="de">Food</option>
                                                        <option value="pl">Fashion</option>
                                                </select>
                                                </div>
                                            </div>
                                            <!-- Row -->
                                            <div class="row ms-0">
                                                <label class="col-md-3 form-label mb-4 ps-0">Post Description :</label>
                                                <div class="mb-4">
                                                    <textarea class="content" name="example"></textarea>
                                                </div>
                                            </div>
                                            <!--End Row-->
                                            <div class="form-group mb-0">
                                                <label class="col-md-3 form-label mb-4">File Upload :</label>
                                                <input type="file" class="dropify" data-height="100">
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <a href="javascript:void(0);" class="btn btn-primary">Post</a>
                                            <a href="javascript:void(0);" class="btn btn-default float-end">Discard</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Recent Posts</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="">
                                                <div class="d-flex overflow-visible">
                                                    <a href="{{url('blog-details')}}" class="card-aside-column br-5 cover-image blog-cover-img" data-bs-image-src="{{asset('build/assets/images/media/19.jpg')}}"></a>
                                                    <div class="ps-3 flex-column">
                                                        <span class="badge bg-danger me-1 mb-1 mt-1">Lifestyle</span>
                                                        <h4><a href="{{url('blog-details')}}">Generator on the Internet..</a></h4>
                                                        <div class="text-muted">Excepteur sint occaecat cupidatat non proident, accusantium sunt in culpa qui officia deserunt mollit anim id est laborum....</div>
                                                    </div>
                                                </div>
                                                <div class="d-flex overflow-visible mt-5">
                                                    <a href="{{url('blog-details')}}" class="card-aside-column br-5 cover-image blog-cover-img1" data-bs-image-src="{{asset('build/assets/images/media/22.jpg')}}"></a>
                                                    <div class="ps-3 flex-column">
                                                        <span class="badge bg-primary me-1 mb-1 mt-1">Business</span>
                                                        <h4><a href="{{url('blog-details')}}">Voluptatem quia voluptas...</a></h4>
                                                        <div class="text-muted">Excepteur sint occaecat cupidatat non proident, accusantium sunt in culpa qui officia deserunt mollit anim id est laborum....</div>
                                                    </div>
                                                </div>
                                                <div class="d-flex overflow-visible mt-5">
                                                    <a href="{{url('blog-details')}}" class="card-aside-column br-5 cover-image blog-cover-img2" data-bs-image-src="{{asset('build/assets/images/media/12.jpg')}}"></a>
                                                    <div class="ps-3 flex-column">
                                                        <span class="badge bg-secondary me-1 mb-1 mt-1">Travel</span>
                                                        <h4><a href="{{url('blog-details')}}">Generator on the Internet..</a></h4>
                                                        <div class="text-muted">Excepteur sint occaecat cupidatat non proident, accusantium sunt in culpa qui officia deserunt mollit anim id est laborum....</div>
                                                    </div>
                                                </div>
                                                <div class="d-flex overflow-visible mt-5">
                                                    <a href="{{url('blog-details')}}" class="card-aside-column br-5 cover-image blog-cover-img3" data-bs-image-src="{{asset('build/assets/images/media/25.jpg')}}"></a>
                                                    <div class="ps-3 flex-column">
                                                        <span class="badge bg-success me-1 mb-1 mt-1">Meeting</span>
                                                        <h4><a href="{{url('blog-details')}}">Voluptatem quia voluptas...</a></h4>
                                                        <div class="text-muted">Excepteur sint occaecat cupidatat non proident, accusantium sunt in culpa qui officia deserunt mollit anim id est laborum....</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Professional Blog Writers</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="">
                                                <div class="d-flex overflow-visible">
                                                    <img class="avatar br-5 avatar-xl me-3" src="{{asset('build/assets/images/users/12.jpg')}}" alt="avatar-img">
                                                    <div class="media-body valign-middle">
                                                        <a href="{{url('profile')}}" class="fw-semibold text-dark">John Paige</a>
                                                        <p class="text-muted mb-0">There are many variations of passages of Lorem Ipsum available ...</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex overflow-visible mt-5">
                                                    <img class="avatar br-5 avatar-xl me-3" src="{{asset('build/assets/images/users/2.jpg')}}" alt="avatar-img">
                                                    <div class="media-body valign-middle">
                                                        <a href="{{url('profile')}}" class="fw-semibold text-dark">Peter Hill</a>
                                                        <p class="text-muted mb-0">There are many variations of passages of Lorem Ipsum available ...</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex overflow-visible mt-5">
                                                    <img class="avatar br-5 avatar-xl me-3" src="{{asset('build/assets/images/users/9.jpg')}}" alt="avatar-img">
                                                    <div class="media-body valign-middle">
                                                        <a href="{{url('profile')}}" class="fw-semibold text-dark">Irene Harris</a>
                                                        <p class="text-muted mb-0">There are many variations of passages of Lorem Ipsum available ...</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex overflow-visible mt-5">
                                                    <img class="avatar br-5 avatar-xl me-3" src="{{asset('build/assets/images/users/4.jpg')}}" alt="avatar-img">
                                                    <div class="media-body valign-middle">
                                                        <a href="{{url('profile')}}" class="fw-semibold text-dark">Harry Fisher</a>
                                                        <p class="text-muted mb-0">There are many variations of passages of Lorem Ipsum available ...</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-1 CLOSED -->

@endsection

@section('scripts')

        <!-- INTERNAL WYSIWYG Editor JS -->
        <script src="{{asset('build/assets/plugins/wysiwyag/jquery.richtext.js')}}"></script>
        <script src="{{asset('build/assets/plugins/wysiwyag/wysiwyag.js')}}"></script>

		<!-- INTERNAL SELECT2 JS -->
		<script src="{{asset('build/assets/plugins/select2/select2.full.min.js')}}"></script>
		@vite('resources/assets/js/select2.js')

        <!-- FILE UPLOADES JS -->
        <script src="{{asset('build/assets/plugins/fileuploads/js/fileupload.js')}}"></script>
        <script src="{{asset('build/assets/plugins/fileuploads/js/file-upload.js')}}"></script>

@endsection
