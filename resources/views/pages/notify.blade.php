
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Notification</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Elements</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Notification</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW-1 OPEN -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Alerts Notifications</h3>
                                        </div>
                                        <div class="card-body text-center">
                                            <div class="example">
                                                <div class="btn-list">
                                                    <a href="javascript:void(0);" class="btn btn-success notice">Success</a>
                                                    <a href="javascript:void(0);" class="btn btn-warning warning">Warning</a>
                                                    <a href="javascript:void(0);" class="btn btn-danger error">Danger</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Alerts Popovers</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-3 col-xl-2 mt-2 mb-2">
                                                    <button type="button" class="btn btn-success btn-block " data-bs-container="body" data-bs-toggle="popover" data-bs-popover-color="default" data-bs-placement="bottom" title="alert sucess" data-bs-content="Well done! You successfully read this important alert message..">
                                                        success
                                                    </button>
                                                </div>
                                                <div class="col-lg-3 col-xl-2 mt-2 mb-2">
                                                    <button type="button" class="btn btn-info btn-block" data-bs-container="body" data-bs-toggle="popover" data-bs-popover-color="default" data-bs-placement="top" title="alert info" data-bs-content="Heads up! This alert needs your attention, but it's not super important...">
                                                        info
                                                    </button>
                                                </div>
                                                <div class="col-lg-3 col-xl-2 mt-2 mb-2">
                                                    <button type="button" class="btn btn-block btn-warning " data-bs-container="body" data-bs-toggle="popover" data-bs-popover-color="default" data-bs-placement="bottom" title="alert warning" data-bs-content="Warning! Best check yo self, you're not looking too good..">
                                                        warning
                                                    </button>
                                                </div>
                                                <div class="col-lg-3 col-xl-2 mt-2 mb-2">
                                                    <button type="button" class="btn btn-block btn-danger" data-bs-container="body" data-bs-toggle="popover" data-bs-popover-color="default" data-bs-placement="bottom" title="alert danger" data-bs-content="Oh snap! Change a few things up and try submitting again.">
                                                    danger
                                                    </button>
                                                </div>
                                                <div class="col-lg-3 col-xl-2 mt-2 mb-2">
                                                    <button type="button" class="btn btn-block btn-secondary" data-bs-container="body" data-bs-toggle="popover" data-bs-popover-color="default" data-bs-placement="top" title="alert secondary" data-bs-content="Error! Please Check u r emial id..">
                                                        secondary
                                                    </button>
                                                </div>
                                                <div class="col-lg-3 col-xl-2 mt-2 mb-2">
                                                    <button type="button" class="btn btn-block btn-primary" data-bs-container="body"  data-bs-toggle="popover" data-bs-popover-color="default" data-bs-placement="top" title="alert primary" data-bs-content="Cool! This alert will close in 3 seconds. The data-delay attribute is in milliseconds.">
                                                        primary
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Alerts style</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12">
                                                    <div class="alert alert-success mb-4">
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                                        <strong>Success Message</strong>
                                                        <hr class="">
                                                        <p>You successfully read this important alert message.</p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-12">
                                                    <div class="alert alert-info mb-4">
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                                        <strong>Info Message</strong>
                                                        <hr class="">
                                                        <p>This alert needs your attention, but it's not super important.</p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-12">
                                                    <div class="alert alert-warning mb-4">
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                                        <strong>Warning Message</strong>
                                                        <hr class="">
                                                        <p>Best check yo self, you're not looking too good.</p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-12">
                                                    <div class="alert alert-danger">
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                                        <strong>Danger Message</strong>
                                                        <hr class="">
                                                        <p>Change a few things up and try submitting again.</p>
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

		<!-- POPOVER JS -->
		@vite('resources/assets/js/popover.js')

		<!-- NOTIFICATIONS JS -->
		<script src="{{asset('build/assets/plugins/notify/js/rainbow.js')}}"></script>
		<script src="{{asset('build/assets/plugins/notify/js/custom-notification.js')}}"></script>
		<script src="{{asset('build/assets/plugins/notify/js/jquery.growl.js')}}"></script>

@endsection
