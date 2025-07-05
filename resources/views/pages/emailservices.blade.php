
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Email Compose</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Pages</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Email Compose</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW-1 OPEN -->
                            <div class="row">
                                <div class="col-lg-4 col-xl-3 col-md-12 col-sm-12">
                                    <div class="card p-3">
                                        <div class="list-group list-group-transparent mb-0 mail-inbox">
                                            <div class="mb-4 text-center">
                                                <a href="{{url('email')}}" class="btn btn-secondary btn-lg btn-block">View Inbox</a>
                                            </div>
                                            <a href="{{url('email')}}" class="list-group-item list-group-item-action d-flex align-items-center active">
                                                <span class="icon me-3"><i class="fe fe-inbox"></i></span>Inbox<span class="ms-auto badge bg-success">14</span>
                                            </a>
                                            <a href="javascript:void(0);" class="list-group-item list-group-item-action d-flex align-items-center">
                                                <span class="icon me-3"><i class="fe fe-send"></i></span>Sent Mail
                                            </a>
                                            <a href="javascript:void(0);" class="list-group-item list-group-item-action d-flex align-items-center">
                                                <span class="icon me-3"><i class="fe fe-alert-circle"></i></span>Important <span class="ms-auto badge bg-danger">3</span>
                                            </a>
                                            <a href="javascript:void(0);" class="list-group-item list-group-item-action d-flex align-items-center">
                                                <span class="icon me-3"><i class="fe fe-star"></i></span>Starred
                                            </a>
                                            <a href="javascript:void(0);" class="list-group-item list-group-item-action d-flex align-items-center">
                                                <span class="icon me-3"><i class="fe fe-file"></i></span>Drafts
                                            </a>
                                            <a href="javascript:void(0);" class="list-group-item list-group-item-action d-flex align-items-center">
                                                <span class="icon me-3"><i class="fe fe-tag"></i></span>Tags
                                            </a>
                                            <a href="javascript:void(0);" class="list-group-item list-group-item-action d-flex align-items-center">
                                                <span class="icon me-3"><i class="fe fe-trash-2"></i></span>Trash
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="online-status d-flex justify-content-between align-items-center mt-4 mb-2 ms-2">
                                            <h5 class="card-title chat ms-3">Chats</h5>
                                            <div class="status offline online"> <h6 class="online text-end">online</h6></div>
                                        </div>
                                        <ul class="mail-chats p-0">
                                            <li class="chat-persons p-3">
                                                <a href="javascript:void(0);">
                                                    <span class="pro-pic"><img src="{{asset('build/assets/images/users/4.jpg')}}" alt=""></span>
                                                    <div class="user">
                                                        <p class="u-name">David</p>
                                                        <p class="u-designation">Python Developer</p>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- LIST PERSON -->
                                            <li class="chat-persons p-3 border-top">
                                                <a href="javascript:void(0);">
                                                    <span class="pro-pic"><img src="{{asset('build/assets/images/users/1.jpg')}}" alt=""></span>
                                                    <div class="user">
                                                        <p class="u-name">Stella Johnson</p>
                                                        <p class="u-designation">SEO Expert</p>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- LIST PERSON -->
                                            <li class="chat-persons p-3 border-top">
                                                <a href="javascript:void(0);">
                                                    <span class="pro-pic"><img src="{{asset('build/assets/images/users/15.jpg')}}" alt=""></span>
                                                    <div class="user">
                                                        <p class="u-name">Marina Michel</p>
                                                        <p class="u-designation">Business Development</p>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- LIST PERSON -->
                                            <li class="chat-persons p-3 border-top">
                                                <a href="javascript:void(0);">
                                                    <span class="pro-pic"><img src="{{asset('build/assets/images/users/2.jpg')}}" alt=""></span>
                                                    <div class="user">
                                                        <p class="u-name">Edward Fletcher</p>
                                                        <p class="u-designation">UI/UX Designer</p>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- LIST PERSON -->
                                        </ul>
                                        <!-- CHAT -->
                                    </div>
                                </div>
                                <div class="col-lg-8 col-xl-9 col-md-12 col-sm-12">
                                    <div class="card email-compose">
                                        <div class="inbox card-body">
                                            <form>
                                                <div class="form-row mb-4">
                                                    <label class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">To:</label>
                                                    <div class="col-9 col-sm-10 col-md-9 col-lg-10">
                                                        <input type="email" class="form-control" id="to2" placeholder="Type email">
                                                    </div>
                                                </div>
                                                <div class="form-row mb-4">
                                                    <label  class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">CC:</label>
                                                    <div class="col-9 col-sm-10 col-md-9 col-lg-10">
                                                        <input type="email" class="form-control" id="cc" placeholder="Type email">
                                                    </div>
                                                </div>
                                                <div class="form-row mb-4">
                                                    <label  class="col-3 col-sm-2 col-md-3 col-lg-2 col-form-label">Subject</label>
                                                    <div class="col-9 col-sm-10 col-md-9 col-lg-10">
                                                        <input type="email" class="form-control" id="subject" placeholder="Type Subject">
                                                    </div>
                                                </div>
                                            </form>
                                            <div class="row">
                                                <div class="col-sm-10 ms-auto col-md-12 col-lg-10">
                                                    <textarea class="content" name="example"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-footer d-sm-flex">
                                            <div class="mt-2 mb-2">
                                                <button type="button" class="btn btn-icon footer-icon mt-2"><i class="fa fa-paperclip text-default"></i></button>
                                                <button type="button" class="btn btn-icon footer-icon mt-2"><i class="fa fa-picture-o text-default"></i></button>
                                                <button type="button" class="btn btn-icon footer-icon mt-2"><i class="fa fa-link text-default"></i></button>
                                                <button type="button" class="btn btn-icon footer-icon mt-2"><i class="fa fa-trash-o text-default"></i></button>
                                                <button type="button" class="btn btn-icon footer-icon mt-2"><i class="fa fa-file text-default"></i></button>
                                            </div>
                                            <div class="btn-list ms-auto my-auto">
                                                <button type="button" class="btn btn-secondary btn-space m-1">Cancel</button>
                                                    <button type="submit" class="btn btn-primary btn-space m-1">Send message</button>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                            </div>
                            <!-- ROW-1 CLOSED -->

@endsection

@section('scripts')

		<!-- INTERNAL WYSIWYG Editor JS -->
		<script src="{{asset('build/assets/plugins/wysiwyag/jquery.richtext.js')}}"></script>
		<script src="{{asset('build/assets/plugins/wysiwyag/wysiwyag.js')}}"></script>

@endsection
