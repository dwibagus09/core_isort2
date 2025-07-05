
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Read Mail</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Pages</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Read Mail</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- Row -->
                            <div class="row">
                                <div class="col-xl-3">
                                    <div class="card">
                                        <div class="list-group list-group-transparent mb-0 mail-inbox pb-3">
                                            <div class="mt-4 mb-4 mx-4 text-center">
                                                <a href="{{url('emailservices')}}" class="btn btn-primary btn-lg d-grid">Compose</a>
                                            </div>
                                            <a href="{{url('email')}}" class="list-group-item d-flex align-items-center active mx-4">
                                                <span class="icons"><i class="ri-mail-line"></i></span> Inbox <span class="ms-auto badge bg-secondary bradius">14</span>
                                            </a>
                                            <a href="javascript:void(0);" class="list-group-item d-flex align-items-center mx-4">
                                                <span class="icons"><i class="ri-mail-open-line"></i></span> Drafts
                                            </a>
                                            <a href="javascript:void(0);" class="list-group-item d-flex align-items-center mx-4">
                                                <span class="icons"><i class="ri-star-line"></i></span> Starred <span class="ms-auto badge bg-success bradius">03</span>
                                            </a>
                                            <a href="javascript:void(0);" class="list-group-item d-flex align-items-center mx-4">
                                                <span class="icons"><i class="ri-price-tag-3-line"></i></span> Tags
                                            </a>
                                            <a href="javascript:void(0);" class="list-group-item d-flex align-items-center mx-4">
                                                <span class="icons"><i class="ri-mail-send-line"></i></span> Sent Mail
                                            </a>
                                            <a href="javascript:void(0);" class="list-group-item d-flex align-items-center mx-4">
                                                <span class="icons"><i class="ri-delete-bin-line"></i></span> Trash
                                            </a>
                                        </div>
                                        <div class="card-body border-top p-0 py-3">
                                            <div class="list-group list-group-transparent mb-0 mail-inbox mx-4">
                                                <a href="javascript:void(0);" class="list-group-item d-flex align-items-center py-2">
                                                    <span class="w-3 h-3 brround bg-primary me-2"></span> Friends
                                                </a>
                                                <a href="javascript:void(0);" class="list-group-item d-flex align-items-center py-2">
                                                    <span class="w-3 h-3 brround bg-secondary me-2"></span> Family
                                                </a>
                                                <a href="javascript:void(0);" class="list-group-item d-flex align-items-center py-2">
                                                    <span class="w-3 h-3 brround bg-success me-2"></span> Social
                                                </a>
                                                <a href="javascript:void(0);" class="list-group-item d-flex align-items-center py-2">
                                                    <span class="w-3 h-3 brround bg-info me-2"></span> Office
                                                </a>
                                                <a href="javascript:void(0);" class="list-group-item d-flex align-items-center py-2">
                                                    <span class="w-3 h-3 brround bg-warning me-2"></span> Work
                                                </a>
                                                <a href="javascript:void(0);" class="list-group-item d-flex align-items-center py-2">
                                                    <span class="w-3 h-3 brround bg-danger me-2"></span> Settings
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-9">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Mail Read</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="email-media">
                                                <div class="mt-0 d-sm-flex">
                                                    <img class="me-2 rounded-circle avatar avatar-lg" src="{{asset('build/assets/images/users/6.jpg')}}" alt="avatar">
                                                    <div class="media-body pt-0">
                                                        <div class="float-end d-none d-md-flex fs-15">
                                                            <small class="me-3 mt-3 text-muted">Sep 13 , 2021 12:45 pm</small>
                                                            <a href="javascript:void(0);" class="me-3 email-icon text-primary bg-primary-transparent" data-bs-toggle="tooltip" title="Rated"><i class="fe fe-star"></i></a>
                                                            <a href="javascript:void(0);" class="me-3 email-icon text-secondary bg-secondary-transparent" data-bs-toggle="tooltip" title="Reply"><i class="fa fa-reply"></i></a>
                                                            <div class="me-3">
                                                                <a href="javascript:void(0);" class="text-danger email-icon bg-danger-transparent" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fe fe-more-horizontal"></i></a>
                                                                <div class="dropdown-menu dropdown-menu-start">
                                                                    <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-share me-2 d-inline-flex"></i> Reply</a>
                                                                    <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-alert-circle me-2 d-inline-flex"></i>Report Spam</a>
                                                                    <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-trash me-2 d-inline-flex"></i>Delete</a>
                                                                    <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-printer me-2 d-inline-flex"></i>Print</a>
                                                                    <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-filter me-2 d-inline-flex"></i>Filter</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="media-title text-dark fw-semibold mt-1">Cherry Blossom <span class="text-muted fw-semibold">( cherryblosso@gmail.com )</span></div>
                                                        <small class="mb-0 text-muted">to Percy Kewshun ( percykewshun@gmail.com ) </small>
                                                        <small class="me-2 d-md-none">June 15 , 2021 10:30 AM</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="eamil-body mt-5">
                                                <h6 class="op-8">Hi Sir/Madam</h6><br><br>
                                                <p class="op-8">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                                    Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </p>
                                                <p class="op-8"> Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia.</p>
                                                <p class="op-8"> Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To
                                                    take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it?</p>
                                                <br><br>
                                                <p class="mb-0 op-8">Thanking you Sir/Madam</p>
                                                <hr>
                                                <div class="email-attch">
                                                    <p class="fw-semibold d-inline-flex">3 Attachments <a class="ms-2" href="{{url('filemanager-details')}}">View</a></p>
                                                </div>
                                                <div class="row attachments-doc">
                                                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 mb-2 mb-sm-0">
                                                        <div class="border overflow-hidden p-0 br-7">
                                                            <a href="{{url('filemanager-details')}}"><img src="{{asset('build/assets/images/media/8.jpg')}}" class="card-img-top" alt="img"></a>
                                                            <div class="p-3 text-center">
                                                                <a href="{{url('filemanager-details')}}" class="fw-semibold fs-15 text-dark">Image.jpg</a>
                                                                <p class="text-muted.ms-2 mb-0 fs-11">(223 KB)</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 mb-2 mb-sm-0">
                                                        <div class="border overflow-hidden p-0 br-7">
                                                            <a href="{{url('filemanager-details')}}"><img src="{{asset('build/assets/images/media/22.jpg')}}" class="card-img-top" alt="img"></a>
                                                            <div class="p-3 text-center">
                                                                <a href="{{url('filemanager-details')}}" class="fw-semibold fs-15 text-dark">Image2.jpg</a>
                                                                <p class="text-muted.ms-2 mb-0 fs-11">(122 KB)</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 mb-2 mb-sm-0">
                                                        <div class="border overflow-hidden p-0 br-7">
                                                            <a href="{{url('filemanager-details')}}"><img src="{{asset('build/assets/images/media/6.jpg')}}" class="card-img-top" alt="img"></a>
                                                            <div class="p-3 text-center">
                                                                <a href="{{url('filemanager-details')}}" class="fw-semibold fs-15 text-dark">Image3.jpg</a>
                                                                <p class="text-muted.ms-2 mb-0 fs-11">(345 KB)</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <a class="btn btn-primary mt-1 mb-1" href="javascript:void(0);"><i class="fa fa-reply"></i> Reply</a>
                                            <a class="btn btn-secondary mt-1 mb-1" href="javascript:void(0);"><i class="fa fa-share"></i> Forward</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--End  Row -->

@endsection

@section('scripts')

		<!-- INTERNAL SELECT2 JS -->
		<script src="{{asset('build/assets/plugins/select2/select2.full.min.js')}}"></script>
		@vite('resources/assets/js/select2.js')

@endsection
