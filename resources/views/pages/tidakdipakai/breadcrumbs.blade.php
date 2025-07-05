
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Breadcrumbs</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Elements</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Breadcrumbs</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW-1 OPEN -->
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Simple Breadcrumbs</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="example">
                                                <ol class="breadcrumb1">
                                                    <li class="breadcrumb-item1 active">Home</li>
                                                    <li class="breadcrumb-item1 active">About</li>
                                                </ol>
                                                <ol class="breadcrumb1">
                                                    <li class="breadcrumb-item1"><a href="javascript:void(0);">Home</a></li>
                                                    <li class="breadcrumb-item1 active">Library</li>
                                                </ol>
                                                <ol class="breadcrumb1">
                                                    <li class="breadcrumb-item1"><a href="javascript:void(0);">Home</a></li>
                                                    <li class="breadcrumb-item1"><a href="javascript:void(0);">Library</a></li>
                                                    <li class="breadcrumb-item1 active">Data</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Breadcrumbs-Center align</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="example">
                                                <div class="breadcrumb-3">
                                                    <ol class="breadcrumb1">
                                                        <li class="breadcrumb-item1"><a href="javascript:void(0);">Home</a></li>
                                                        <li class="breadcrumb-item1 active">about</li>
                                                    </ol>
                                                </div>
                                                <div class="breadcrumb-4">
                                                    <ol class="breadcrumb1">
                                                        <li class="breadcrumb-item1"><a href="javascript:void(0);">Home</a></li>
                                                        <li class="breadcrumb-item1 active">Library</li>
                                                    </ol>
                                                </div>
                                                <div class="breadcrumb-5">
                                                    <ol class="breadcrumb1 mb-0">
                                                        <li class="breadcrumb-item1"><a href="javascript:void(0);">Home</a></li>
                                                        <li class="breadcrumb-item1"><a href="javascript:void(0);">Library</a></li>
                                                        <li class="breadcrumb-item1 active">Data</li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Breadcrumbs-Right align</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="example">
                                                <div class="breadcrumb-1">
                                                    <ol class="breadcrumb1">
                                                        <li class="breadcrumb-item1"><a href="javascript:void(0);">Home</a></li>
                                                        <li class="breadcrumb-item1 active">about</li>
                                                    </ol>
                                                </div>
                                                <div class="breadcrumb-2">
                                                    <ol class="breadcrumb1">
                                                        <li class="breadcrumb-item1"><a href="javascript:void(0);">Home</a></li>
                                                        <li class="breadcrumb-item1"><a href="javascript:void(0);">Library</a></li>
                                                    </ol>
                                                </div>
                                                <div class="breadcrumb-6">
                                                    <ol class="breadcrumb1 mb-0">
                                                        <li class="breadcrumb-item1"><a href="javascript:void(0);">Home</a></li>
                                                        <li class="breadcrumb-item1"><a href="javascript:void(0);">Library</a></li>
                                                        <li class="breadcrumb-item1 active">Data</li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-md-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Color Breadcrumbs</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="example">
                                                <ol class="breadcrumb1 bg-info">
                                                    <li class="breadcrumb-item active"><i class="fa fa-star-o me-2 text-transparant" aria-hidden="true"></i>Home</li>
                                                </ol>
                                                <ol class="breadcrumb1 bg-success">
                                                    <li class="breadcrumb-item1"><i class="fa fa-star-o me-2 text-white" aria-hidden="true"></i><a class="text-white" href="javascript:void(0);">Home</a></li>
                                                    <li class="breadcrumb-item active"><i class="fa fa-star-o mx-2 text-transparant" aria-hidden="true"></i>Library</li>
                                                </ol>
                                                <ol class="breadcrumb1 bg-secondary">
                                                    <li class="breadcrumb-item"><i class="fa fa-star-o me-2 text-white" aria-hidden="true"></i><a class="text-white" href="javascript:void(0);">Home</a></li>
                                                    <li class="breadcrumb-item"><i class="fa fa-star-o mx-2 text-white" aria-hidden="true"></i><a class="text-white" href="javascript:void(0);">Library</a></li>
                                                    <li class="breadcrumb-item active"><i class="fa fa-star-o mx-2 text-transparant" aria-hidden="true"></i>Data</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                            </div>

@endsection

@section('scripts')



@endsection
