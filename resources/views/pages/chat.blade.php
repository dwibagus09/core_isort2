
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Chat</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Components</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Chat</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW-1 OPEN -->
                            <div class="row chatbox">
                                <div class="col-sm-12 col-md-12 col-lg-5 col-xl-4 col-xxl-4">
                                    <div class="card">
                                        <div class="p-4">
                                            <div class="input-group">
                                                <input type="text" class="form-control  bg-white" placeholder="Search">
                                                <div class=" ">
                                                    <button type="button" class="btn btn-primary ">
                                                        <i class="fa fa-search" aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="contacts_body p-0">
                                            <ul class="contacts mb-0">
                                                <li class="active">
                                                    <div class="d-flex bd-highlight">
                                                        <div class="img_cont">
                                                            <img src="{{asset('build/assets/images/users/3.jpg')}}" class="rounded-circle user_img" alt="img">
                                                        </div>
                                                        <div class="user_info">
                                                            <h6 class="mt-1 mb-0 ">Maryam Naz</h6>
                                                            <span class="dot-label bg-success"></span><span class="me-3">online</span>
                                                        </div>
                                                        <div class="float-end text-end ms-auto mt-auto mb-auto">
                                                            <small>01-02-2019</small>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="d-flex bd-highlight">
                                                        <div class="img_cont">
                                                            <img src="{{asset('build/assets/images/users/1.jpg')}}" class="rounded-circle user_img" alt="img">
                                                        </div>
                                                        <div class="user_info">
                                                            <h6 class="mt-1 mb-0 ">Sahar Darya</h6>
                                                            <span class="dot-label bg-danger"></span><span class="me-3">offline</span><small class="text-muted"></small>
                                                        </div>
                                                        <div class="float-end text-end ms-auto mt-auto mb-auto"><small>01-02-2019</small></div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="d-flex bd-highlight">
                                                        <div class="img_cont">
                                                            <img src="{{asset('build/assets/images/users/12.jpg')}}" class="rounded-circle user_img" alt="img">
                                                        </div>
                                                        <div class="user_info">
                                                            <h6 class="mt-1 mb-0 ">Yolduz Rafi</h6>
                                                            <span class="dot-label bg-success"></span><span class="me-3">online</span>
                                                        </div>
                                                        <div class="float-end text-end ms-auto mt-auto mb-auto"><small>02-02-2019</small></div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="d-flex bd-highlight">
                                                        <div class="img_cont">
                                                            <img src="{{asset('build/assets/images/users/15.jpg')}}" class="rounded-circle user_img" alt="img">
                                                        </div>
                                                        <div class="user_info">
                                                            <h6 class="mt-1 mb-0 ">Nargis Hawa</h6>
                                                            <span class="dot-label bg-danger"></span><span class="me-3">offline</span><small class="text-muted"></small>
                                                        </div>
                                                        <div class="float-end text-end ms-auto mt-auto mb-auto"><small>02-02-2019</small></div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="d-flex bd-highlight">
                                                        <div class="img_cont">
                                                            <img src="{{asset('build/assets/images/users/17.jpg')}}" class="rounded-circle user_img" alt="img">
                                                        </div>
                                                        <div class="user_info">
                                                            <h6 class="mt-1 mb-0 ">Khadija Mehr</h6>
                                                            <span class="dot-label bg-danger"></span><span class="me-3">offline</span><small class="text-muted"></small>
                                                        </div>
                                                        <div class="float-end text-end ms-auto mt-auto mb-auto"><small>03-02-2019</small></div>
                                                    </div>
                                                </li>
                                                <li class="border-bottom">
                                                    <div class="d-flex bd-highlight">
                                                        <div class="img_cont">
                                                            <img src="{{asset('build/assets/images/users/17.jpg')}}" class="rounded-circle user_img" alt="img">
                                                        </div>
                                                        <div class="user_info">
                                                            <h6 class="mt-1 mb-0 ">Angela Allan </h6>
                                                            <span class="dot-label bg-success"></span><span class="me-3">offline</span><small class="text-muted"></small>
                                                        </div>
                                                        <div class="float-end text-end ms-auto mt-auto mb-auto"><small>03-02-2019</small></div>
                                                    </div>
                                                </li>
                                                <li class="border-bottom-0">
                                                    <div class="d-flex bd-highlight">
                                                        <div class="img_cont">
                                                            <img src="{{asset('build/assets/images/users/18.jpg')}}" class="rounded-circle user_img" alt="img">
                                                        </div>
                                                        <div class="user_info">
                                                            <h6 class="mt-1 mb-0 ">Joyce Stewart </h6>
                                                            <span class="dot-label bg-danger"></span><span class="me-3">offline</span><small class="text-muted"></small>
                                                        </div>
                                                        <div class="float-end text-end ms-auto mt-auto mb-auto"><small>04-02-2019</small></div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div><!-- COL END -->
                                <div class="col-sm-12 col-md-12 col-lg-7 col-xl-4 col-xxl-5 chat">
                                    <div class="card">
                                        <!-- ACTION HEDAER OPEN -->
                                        <div class="action-header clearfix">
                                            <div class="float-start hidden-xs d-flex ms-2">
                                                <div class="img_cont me-3">
                                                    <img src="{{asset('build/assets/images/users/10.jpg')}}" class="rounded-circle user_img" alt="img">
                                                </div>
                                                <div class="align-items-center mt-2">
                                                    <h4 class="mb-0 fw-semibold">Elizabeth Dyer</h4>
                                                    <span class="dot-label bg-success"></span><span class="me-3">online</span>
                                                </div>
                                            </div>
                                            <ul class="ah-actions actions align-items-center">
                                                <li class="call-icon">
                                                    <a href="" class="d-done d-md-flex">
                                                        <i class="icon icon-phone"></i>
                                                    </a>
                                                </li>
                                                <li class="video-icon">
                                                    <a href="" class="d-done d-md-flex">
                                                        <i class="icon icon-camrecorder"></i>
                                                    </a>
                                                </li>
                                                <li class="video-icon">
                                                    <a href="" class="d-done d-md-flex">
                                                        <i class="icon icon-user-follow"></i>
                                                    </a>
                                                </li>
                                                <li class="dropdown">
                                                    <a href="" data-bs-toggle="dropdown" aria-expanded="true">
                                                        <i class="icon icon-options-vertical"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><i class="fa fa-user-circle"></i> View profile</li>
                                                        <li><i class="fa fa-users"></i> Add to close friends</li>
                                                        <li><i class="fa fa-plus"></i> Add to group</li>
                                                        <li><i class="fa fa-ban"></i> Block</li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- ACTION HEADER END -->

                                        <!-- MSG CARD-BODY OPEN -->
                                        <div class="card-body msg_card_body">
                                            <div class="chat-box-single-line">
                                                <abbr class="timestamp">February 1st, 2019</abbr>
                                            </div>
                                            <div class="d-flex justify-content-start mb-4">
                                                <div class="img_cont_msg">
                                                    <img src="{{asset('build/assets/images/users/10.jpg')}}" class="rounded-circle user_img_msg" alt="img">
                                                </div>
                                                <div class="msg_cotainer">
                                                    Hi, how are you Jenna Side?
                                                    <span class="msg_time">8:40 AM, Today</span>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end mb-4">
                                                <div class="msg_cotainer_send">
                                                    Hi Elizabeth Dyer i am good tnx how about you?
                                                    <span class="msg_time_send">8:55 AM, Today</span>
                                                </div>
                                                <div class="img_cont_msg">
                                                    <img src="{{asset('build/assets/images/users/15.jpg')}}" class="rounded-circle user_img_msg" alt="img">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-start mb-4">
                                                <div class="img_cont_msg">
                                                    <img src="{{asset('build/assets/images/users/10.jpg')}}" class="rounded-circle user_img_msg" alt="img">
                                                </div>
                                                <div class="msg_cotainer">
                                                    I am good too, thank you for your chat template
                                                    <span class="msg_time">9:00 AM, Today</span>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end mb-4">
                                                <div class="msg_cotainer_send">
                                                    You welcome Elizabeth Dyer
                                                    <span class="msg_time_send">9:05 AM, Today</span>
                                                </div>
                                                <div class="img_cont_msg">
                                                    <img src="{{asset('build/assets/images/users/15.jpg')}}" class="rounded-circle user_img_msg" alt="img">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-start mb-4">
                                                <div class="img_cont_msg">
                                                    <img src="{{asset('build/assets/images/users/10.jpg')}}" class="rounded-circle user_img_msg" alt="img">
                                                </div>
                                                <div class="msg_cotainer">
                                                    Yo, Can you update Views?
                                                    <span class="msg_time">9:07 AM, Today</span>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end mb-4">
                                                <div class="msg_cotainer_send">
                                                    But I must explain to you how all this mistaken  born and I will give some images
                                                    <span class="msg_time_send">9:10 AM, Today</span>
                                                </div>
                                                <div class="img_cont_msg">
                                                    <img src="{{asset('build/assets/images/users/15.jpg')}}" class="rounded-circle user_img_msg" alt="img">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-start mb-4">
                                                <div class="img_cont_msg">
                                                    <img src="{{asset('build/assets/images/users/10.jpg')}}" class="rounded-circle user_img_msg" alt="img">
                                                </div>
                                                <div class="msg_cotainer">
                                                    Okay Bye, text you later..
                                                    <span class="msg_time">9:12 AM, Today</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- MSG CARD-BODY END -->
                                        <!-- CARD-FOOTER OPEN -->
                                        <div class="card-footer">
                                            <div class="msb-reply d-flex bg-transparent">
                                                <span class="input-group-text  attach_btn">
                                                    <i class="fe fe-paperclip me-2"></i>
                                                    <i class="fe fe-mic me-2"></i>
                                                    <i class="fa fa-smile-o"></i>
                                                </span>
                                                <textarea placeholder="Typing a message here..." class="border-0"></textarea>
                                                <button><i class="fa fa-paper-plane-o"></i></button>
                                            </div>
                                        </div><!-- CARD FOOTER END -->
                                    </div>
                                </div><!-- COL END -->

                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4 col-xxl-3 chat">
                                    <div class="card">
                                        <div class="main-content-app profile-details-main">
                                            <div class="card-body pb-0">
                                                <div class="">
                                                    <nav class="nav float-end">
                                                        <div class="dropdown">
                                                            <a class="nav-link text-muted fs-20 p-0 ps-3" href="javascript:void(0);" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-user me-1"></i> Profile</a>
                                                                <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-edit me-1"></i> Edit</a>
                                                                <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-users me-1"></i> New Group</a>
                                                                <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-settings me-1"></i> Settings</a>
                                                                <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-trash-2 me-1"></i> Delete</a>
                                                            </div>
                                                        </div>
                                                    </nav>

                                                    <div class="text-center chat-image p-4 pb-0 mb-4 br-5">
                                                        <div class="avatar avatar-xxl chat-profile mb-3 brround">
                                                            <a class="" href="{{url('profile')}}"><img alt="avatar" src="{{asset('build/assets/images/users/7.jpg')}}" class="brround"></a>
                                                        </div>
                                                        <div class="main-chat-msg-name">
                                                            <a href="{{url('profile')}}">
                                                                <h5 class="mb-1 text-dark fw-semibold">Percy Kewshun</h5>
                                                            </a>
                                                            <span class="dot-label bg-success"></span><small class="me-3">Available</small>
                                                            <p class="text-muted mt-0 mb-1 pt-0 fs-13">Senior Web Designer</p>
                                                        </div>
                                                    </div>
                                                    <div class="">
                                                        <h6 class="mb-3">Contact Details :</h6>
                                                        <div class="d-flex mb-2">
                                                            <div>
                                                                <a class="nav-link border rounded-pill chat-profile me-2" href="javascript:void(0);"><i class="fe fe-mail"></i></a>
                                                            </div>
                                                            <div class="ms-2">
                                                                <p class="fs-13 fw-semibold mb-0">Email</p>
                                                                <p class="fs-12 text-muted">gracie435@gmail.com.</p>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex mb-2 mt-2">
                                                            <div>
                                                                <a class="nav-link border rounded-pill chat-profile me-2" href="javascript:void(0);"><i class="fe fe-phone"></i></a>
                                                            </div>
                                                            <div class="ms-2">
                                                                <p class="fs-13 fw-semibold mb-0">Phone</p>
                                                                <p class="fs-12 text-muted">+123(45)-678-90.</p>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex mb-2 mt-2">
                                                            <div>
                                                                <a class="nav-link border rounded-pill chat-profile me-2" href="javascript:void(0);"><i class="fe fe-map-pin"></i></a>
                                                            </div>
                                                            <div class="ms-2">
                                                                <p class="fs-13 fw-semibold mb-0">Address</p>
                                                                <p class="fs-12 text-muted">2nd street,houston texas,united states.</p>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex mb-2 mt-2">
                                                            <div>
                                                                <a class="nav-link border rounded-pill chat-profile me-2" href="javascript:void(0);"><i class="fe fe-linkedin"></i></a>
                                                            </div>
                                                            <div class="ms-2">
                                                                <p class="fs-13 fw-semibold mb-0">Linkedin</p>
                                                                <p class="fs-12 text-muted">@895434</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="">
                                                        <h6 class="mt-5 mb-0">Shared Files :</h6>
                                                        <div class="">
                                                            <div class="row">
                                                                <div class="border-0 p-0 mb-4 pt-4">
                                                                    <div class="media mt-0 border br-7">
                                                                        <div class="ps-0 me-3"><i class="fa fa-folder-open shared-files text-muted"></i></div>
                                                                        <div class="media-body">
                                                                            <div class="d-flex align-items-center">
                                                                                <div class="mt-0">
                                                                                    <h5 class="mb-1 fs-13 fw-semibold text-dark"> Demo_Document</h5>
                                                                                    <p class="mb-0 fs-13 text-muted d-inline-flex lh-0">ppt<span class="fs-11 ms-2">1.2 mb</span></p>
                                                                                </div>
                                                                                <span class="ms-auto fs-14">
                                                                                    <span class="float-end">
                                                                                        <span class="op-7 text-muted"><i class="fe fe-download"></i></span>
                                                                                </span>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="border-0 p-0 mb-4">
                                                                    <div class="media mt-0 border br-7">
                                                                        <div  class="ps-0 me-3"><i class="fa fa-image shared-files text-muted"></i></div>
                                                                        <div class="media-body">
                                                                            <div class="d-flex align-items-center">
                                                                                <div class="mt-0">
                                                                                    <h5 class="mb-1 fs-13 fw-semibold text-dark">Image_attachment</h5>
                                                                                    <p class="mb-0 fs-13 text-muted d-inline-flex lh-0">img<span class="fs-11 ms-2">12 mb</span></p>
                                                                                </div>
                                                                                <span class="ms-auto fs-14">
                                                                                    <span class="float-end">
                                                                                            <span class="op-7 text-muted"><i class="fe fe-download"></i></span>
                                                                                </span>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="border-0 p-0 mb-0">
                                                                    <div class="media mt-0 border br-7">
                                                                        <div  class="ps-0 me-3"><i class="fa fa-envelope shared-files text-muted"></i></div>
                                                                        <div class="media-body">
                                                                            <div class="d-flex align-items-center">
                                                                                <div class="mt-0">
                                                                                    <h5 class="mb-1 fs-13 fw-semibold text-dark"> Video_attachment</h5>
                                                                                    <p class="mb-0 fs-13 text-muted d-inline-flex lh-0">Video<span class="fs-12 ms-2">16 mb</span></p>
                                                                                </div>
                                                                                <span class="ms-auto fs-14">
                                                                                    <span class="float-end">
                                                                                            <span class="op-7 text-muted"><i class="fe fe-download"></i></span>
                                                                                </span>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="">
                                                        <h5 class="mb-3 pt-5">Media :</h5>
                                                        <ul id="lightgallery" class="list-unstyled row">
                                                            <li class="col-xs-6 col-sm-4 col-md-4 col-xl-4 mb-2 border-bottom-0 px-1" data-responsive="{{asset('build/assets/images/media/27.jpg')}}" data-src="{{asset('build/assets/images/media/27.jpg')}}" data-sub-html="<h4>Gallery Image 1</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
                                                                <a href="javascript:void(0);">
                                                                    <img class="img-responsive br-5" src="{{asset('build/assets/images/media/27.jpg')}}" alt="Thumb-1">
                                                                </a>
                                                            </li>
                                                            <li class="col-xs-6 col-sm-4 col-md-4 col-xl-4 mb-2 border-bottom-0 px-1" data-responsive="{{asset('build/assets/images/media/28.jpg')}}" data-src="{{asset('build/assets/images/media/28.jpg')}}" data-sub-html="<h4>Gallery Image 2</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
                                                                <a href="javascript:void(0);">
                                                                    <img class="img-responsive br-5" src="{{asset('build/assets/images/media/28.jpg')}}" alt="Thumb-2">
                                                                </a>
                                                            </li>
                                                            <li class="col-xs-6 col-sm-4 col-md-4 col-xl-4 mb-2 border-bottom-0 px-1" data-responsive="{{asset('build/assets/images/media/29.jpg')}}" data-src="{{asset('build/assets/images/media/29.jpg')}}" data-sub-html="<h4>Gallery Image 3</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
                                                                <a href="javascript:void(0);">
                                                                    <img class="img-responsive br-5" src="{{asset('build/assets/images/media/29.jpg')}}" alt="Thumb-1">
                                                                </a>
                                                            </li>
                                                            <li class="col-xs-6 col-sm-4 col-md-4 col-xl-4 mb-2 border-bottom-0 px-1" data-responsive="{{asset('build/assets/images/media/30.jpg')}}" data-src="{{asset('build/assets/images/media/30.jpg')}}" data-sub-html=" <h4>Gallery Image 4</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
                                                                <a href="javascript:void(0);">
                                                                    <img class="img-responsive br-5" src="{{asset('build/assets/images/media/30.jpg')}}" alt="Thumb-2">
                                                                </a>
                                                            </li>
                                                            <li class="col-xs-6 col-sm-4 col-md-4 col-xl-4 mb-2 border-bottom-0 px-1" data-responsive="{{asset('build/assets/images/media/31.jpg')}}" data-src="{{asset('build/assets/images/media/31.jpg')}}" data-sub-html="<h4>Gallery Image 5</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
                                                                <a href="javascript:void(0);">
                                                                    <img class="img-responsive br-5" src="{{asset('build/assets/images/media/31.jpg')}}" alt="Thumb-1">
                                                                </a>
                                                            </li>
                                                            <li class="col-xs-6 col-sm-4 col-md-4 col-xl-4 mb-2 border-bottom-0 px-1" data-responsive="{{asset('build/assets/images/media/32.jpg')}}" data-src="{{asset('build/assets/images/media/32.jpg')}}" data-sub-html="<h4>Gallery Image 6</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
                                                                <a href="javascript:void(0);">
                                                                    <img class="img-responsive br-5" src="{{asset('build/assets/images/media/32.jpg')}}" alt="Thumb-2">
                                                                </a>
                                                            </li>
                                                        </ul>
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



@endsection
