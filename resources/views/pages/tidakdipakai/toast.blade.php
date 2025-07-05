
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Toast</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Elements</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Toast</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW-1 OPEN -->
                            <div class="row">
                                <div class="col-xl-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h3 class="card-title">Basic Toasts</h3>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="p-4">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                                            <div class="toast-header">
                                                                <img src="{{asset('build/assets/images/brand/logo-2.png')}}" alt="" class="me-2" height="18">
                                                                <strong class="me-auto">Bootstrap</strong>
                                                                <small class="ms-2">11 mins ago</small>
                                                                <button aria-label="Close" class="btn-close fs-20" data-bs-dismiss="toast"><span aria-hidden="true">×</span></button>
                                                            </div>
                                                            <div class="toast-body">
                                                                Hello, world! This is a toast message.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mt-2 mt-md-0">
                                                        <button type="button" class="btn btn-primary mt-5" id="liveToastBtn">Show live toast</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h3 class="card-title">Translucent Toasts</h3>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="p-4 bg-light br-7">
                                                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                                    <div class="toast-header">
                                                        <img src="{{asset('build/assets/images/brand/logo-2.png')}}" alt="" class="me-2" height="18">
                                                        <strong class="me-auto">Bootstrap</strong>
                                                        <small class="ms-2">11 mins ago</small>
                                                        <button aria-label="Close" class="btn-close fs-20" data-bs-dismiss="toast"><span aria-hidden="true">×</span></button>
                                                    </div>
                                                    <div class="toast-body">
                                                        Hello, world! This is a toast message.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-1 CLOSED -->

                            <!-- ROW-2 OPEN  -->
                            <div class="row">
                                <div class="col-xl-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h3 class="card-title">Custom content Toasts</h3>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 col-xl-6 mt-1">
                                                    <div class="toast align-items-center show mb-4" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                                        <div class="d-flex">
                                                            <div class="toast-body">
                                                                Hello, world! This is a toast message.
                                                            </div>
                                                            <button aria-label="Close" class="btn-close fs-20 ms-auto mt-2 pe-2" data-bs-dismiss="toast"><span aria-hidden="true">×</span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-xl-6 mt-1">
                                                    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                                        <div class="toast-body">
                                                            Hello, world! This is a toast message.
                                                            <div class="mt-2 pt-2 border-top">
                                                                <button type="button" class="btn btn-primary btn-sm">Take action</button>
                                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="toast">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h3 class="card-title">Color Toasts</h3>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="toast align-items-center text-white bg-primary border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                                        <div class="d-flex">
                                                            <div class="toast-body">
                                                                Hello, world! This is a toast message.
                                                            </div>
                                                            <button aria-label="Close" class="btn-close fs-20 ms-auto mt-2 pe-2 text-white fw-normal" data-bs-dismiss="toast"><span aria-hidden="true">×</span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-2 mt-md-0">
                                                    <div class="toast align-items-center text-white bg-secondary border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                                        <div class="d-flex">
                                                            <div class="toast-body">
                                                                Hello, world! This is a toast message.
                                                            </div>
                                                            <button aria-label="Close" class="btn-close fs-20 ms-auto mt-2 pe-2 text-white fw-normal" data-bs-dismiss="toast"><span aria-hidden="true">×</span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-2">
                                                    <div class="toast align-items-center text-white bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                                        <div class="d-flex">
                                                            <div class="toast-body">
                                                                Hello, world! This is a toast message.
                                                            </div>
                                                            <button aria-label="Close" class="btn-close fs-20 ms-auto mt-2 pe-2 text-white fw-normal" data-bs-dismiss="toast"><span aria-hidden="true">×</span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-2">
                                                    <div class="toast align-items-center text-white bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                                        <div class="d-flex">
                                                            <div class="toast-body">
                                                                Hello, world! This is a toast message.
                                                            </div>
                                                            <button aria-label="Close" class="btn-close fs-20 ms-auto mt-2 pe-2 text-white fw-normal" data-bs-dismiss="toast"><span aria-hidden="true">×</span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-2 CLOSED -->

                            <!-- ROW-3 OPEN -->
                            <div class="row">
                                <div class="col-xl-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h3 class="card-title">Stacking Toasts</h3>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="p-4 bg-light br-7">
                                                <div class="toast-container">
                                                    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                                        <div class="toast-header">
                                                            <img src="{{asset('build/assets/images/brand/logo-2.png')}}" alt="" class="me-2" height="18">
                                                            <strong class="me-auto">Bootstrap</strong>
                                                            <small class="text-muted ms-3">just now</small>
                                                            <button aria-label="Close" class="btn-close fs-20" data-bs-dismiss="toast"><span aria-hidden="true">×</span></button>
                                                        </div>
                                                        <div class="toast-body">
                                                            See? Just like this.
                                                        </div>
                                                    </div>
                                                    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                                        <div class="toast-header">
                                                            <img src="{{asset('build/assets/images/brand/logo-2.png')}}" alt="" class="me-2" height="18">
                                                            <strong class="me-auto">Bootstrap</strong>
                                                            <small class="text-muted ms-3">2 secs ago</small>
                                                            <button aria-label="Close" class="btn-close fs-20" data-bs-dismiss="toast"><span aria-hidden="true">×</span></button>
                                                        </div>
                                                        <div class="toast-body">
                                                            Heads up, toasts will stack automatically
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h3 class="card-title">Placement Toast</h3>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="p-4 bg-light br-7">
                                                <div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center w-100 h-180">
                                                    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                                        <div class="toast-header">
                                                            <img src="{{asset('build/assets/images/brand/logo-2.png')}}" alt="" class="me-2" height="18">
                                                            <strong class="me-auto">Bootstrap</strong>
                                                            <small class="ms-2 fs-12">11 mins ago</small>
                                                            <button aria-label="Close" class="btn-close fs-20" data-bs-dismiss="toast"><span aria-hidden="true">×</span></button>
                                                        </div>
                                                        <div class="toast-body">
                                                            Hello, world! This is a toast message.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-3 CLOSED -->

                            <!-- Live Toast-->
                            <div class="position-fixed bottom-0 end-0 p-3">
                                <div id="liveToast" class="toast fade" role="alert" aria-live="assertive" aria-atomic="true">
                                    <div class="toast-header">
                                        <img src="{{asset('build/assets/images/brand/logo-2.png')}}" alt="" class="me-2" height="18">
                                        <strong class="me-auto">Bootstrap</strong>
                                        <small>11 mins ago</small>
                                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"><span aria-hidden="true">×</span></button>
                                    </div>
                                    <div class="toast-body">
                                        Hello, world! This is a toast message.
                                    </div>
                                </div>
                            </div>
                            <!-- /Live Toast-->

@endsection

@section('scripts')



@endsection
