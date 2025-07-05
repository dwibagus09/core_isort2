
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Sweet Alert</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Components</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Sweet Alert</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW-1 OPEN -->
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Basic Sweet-alert</h3>
                                        </div>
                                        <div class="card-body">
                                            <table class="table card-table border">
                                                <tr>
                                                    <td>Title</td>
                                                    <td><input type='text' class="form-control" placeholder='Title text' id='title'></td>
                                                </tr>
                                                <tr>
                                                    <td>Message</td>
                                                    <td><input type='text' class="form-control" placeholder='Your message' id='message'></td>
                                                </tr>
                                                <tr>
                                                    <td colspan='2' class="mt-5 text-center">
                                                        <input type='button' class="btn btn-primary mt-2 border-0" value='Simple alert' id='but1'>&nbsp;
                                                        <input type='button' class="btn btn-primary mt-2 border-0" value='Alert with title' id='but2'>&nbsp;
                                                        <input type='button' class="btn btn-primary mt-2 border-0" value='Alert with image' id='but3'>&nbsp;
                                                        <input type='button' class="btn btn-primary mt-2 border-0" value='With timer' id='but4'>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="card text-center">
                                        <div class="card-header">
                                            <h3 class="card-title">Alert Types</h3>
                                        </div>
                                        <div class="card-body">
                                            <p>You can show following types of message box &#8211; You need to specify this in <code>type</code> property as value.</p>
                                            <input type='button' class="btn btn-success mt-2" value='success alert' id='click'>&nbsp;
                                            <input type='button' class="btn btn-warning mt-2" value='Warning alert' id='click1'>&nbsp;
                                            <input type='button' class="btn btn-danger mt-2" value='Danger alert' id='click2'>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                            </div>
                            <!-- ROW-1 CLOSED -->

@endsection

@section('scripts')

        <!-- SWEET-ALERT JS -->
        <script src="{{asset('build/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		@vite('resources/assets/js/sweet-alert.js')

@endsection
