
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Pagination</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Elements</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Pagination</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW-1 OPEN -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card overflow-hidden">
                                        <div class="card-header">
                                            <h3 class="card-title">Basic pagination</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="example">
                                                <div class="pagination-wrapper">
                                                    <nav aria-label="Page navigation">
                                                        <ul class="pagination mb-0">
                                                            <li class="page-item active">
                                                                <a class="page-link" href="javascript:void(0);">1</a>
                                                            </li>
                                                            <li class="page-item">
                                                                <a class="page-link" href="javascript:void(0);">2</a>
                                                            </li>
                                                            <li class="page-item">
                                                                <a class="page-link" href="javascript:void(0);">3</a>
                                                            </li>
                                                            <li class="page-item">
                                                                <a class="page-link" href="javascript:void(0);">4</a>
                                                            </li>
                                                            <li class="page-item">
                                                                <a class="page-link" href="javascript:void(0);">5</a>
                                                            </li>
                                                            <li class="page-item">
                                                                <a aria-label="Next" class="page-link" href="javascript:void(0);"><i class="fa fa-angle-right"></i></a>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                                </div>
                                            </div>
                                            <!-- PAGINATION-WRAPPER -->
                                        </div>
                                    </div>
                                </div><!-- COL END -->
                            </div>
                            <!-- ROW-=1 CLOSED -->

                            <!-- ROW-2 OPEN -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Basic pagination</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="example">
                                                <ul class="pagination">
                                                    <li class="page-item page-prev disabled">
                                                        <a class="page-link" href="javascript:void(0);" tabindex="-1">Prev</a>
                                                    </li>
                                                    <li class="page-item active"><a class="page-link" href="javascript:void(0);">1</a></li>
                                                    <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                                                    <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                                                    <li class="page-item"><a class="page-link" href="javascript:void(0);">4</a></li>
                                                    <li class="page-item page-next">
                                                        <a class="page-link" href="javascript:void(0);">Next</a>
                                                    </li>
                                                </ul>
                                                <!-- PAGINATION-WRAPPER -->
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL END -->
                            </div>
                            <!-- ROW-2 CLOSED -->

                            <!-- R0W-3 OPEN -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Model Pagination</h3>
                                        </div>
                                        <div class="card-body ">
                                            <div class="example">
                                                <ul class="pagination mg-b-0 page-0">
                                                    <li class="page-item">
                                                        <a aria-label="Last" class="page-link" href="javascript:void(0);"><i class="fa fa-angle-double-left"></i></a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a aria-label="Next" class="page-link" href="javascript:void(0);"><i class="fa fa-angle-left"></i></a>
                                                    </li>

                                                    <li class="page-item">
                                                        <a class="page-link" href="javascript:void(0);">2</a>
                                                    </li>
                                                    <li class="page-item active">
                                                        <a class="page-link" href="javascript:void(0);">3</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a class="page-link hidden-xs-down" href="javascript:void(0);">4</a>
                                                    </li>

                                                    <li class="page-item">
                                                        <a aria-label="Next" class="page-link" href="javascript:void(0);"><i class="fa fa-angle-right"></i></a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a aria-label="Last" class="page-link" href="javascript:void(0);"><i class="fa fa-angle-double-right"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- PAGINATION-WRAPPER -->
                                        </div>
                                    </div>
                                    <!-- SECTION-WRAPPER -->
                                </div><!-- COL-END -->
                            </div>
                            <!-- ROW-3 CLOSED -->

                            <!-- ROW-4 OPEN -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Model Pagination2</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="example">
                                                <nav aria-label="Page navigation">
                                                    <ul class="pagination pagination-success mb-0">
                                                        <li class="page-item page-0">
                                                            <a aria-label="Next" class="page-link" href="javascript:void(0);"><i class="fa fa-angle-left"></i></a>
                                                        </li>
                                                        <li class="page-item">
                                                            <a aria-label="Last" class="page-link" href="javascript:void(0);"><i class="fa fa-angle-double-left"></i></a>
                                                        </li>
                                                        <li class="page-item active">
                                                            <a class="page-link" href="javascript:void(0);">4</a>
                                                        </li>
                                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                                        <li class="page-item">
                                                            <a class="page-link" href="javascript:void(0);">10</a>
                                                        </li>
                                                        <li class="page-item page-0">
                                                            <a aria-label="Next" class="page-link" href="javascript:void(0);"><i class="fa fa-angle-right"></i></a>
                                                        </li>
                                                        <li class="page-item">
                                                            <a aria-label="Last" class="page-link" href="javascript:void(0);"><i class="fa fa-angle-double-right"></i></a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                                <!-- PAGINATION-WRAPPER -->
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL END -->
                            </div>
                            <!-- ROW-4 CLOSED -->

                            <!-- ROW-5 -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Model Pagination2</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="example">
                                                <nav aria-label="Page navigation example">
                                                    <ul class="pagination">
                                                        <li class="page-item">
                                                            <a class="page-link" href="javascript:void(0);" aria-label="Previous">
                                                                <i class="fa fa-angle-left"></i>
                                                                <span class="sr-only">Previous</span>
                                                            </a>
                                                        </li>
                                                        <li class="page-item"><a class="page-link" href="javascript:void(0);">1</a></li>
                                                        <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                                                        <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                                                        <li class="page-item">
                                                            <a class="page-link" href="javascript:void(0);" aria-label="Next">
                                                                <i class="fa fa-angle-right"></i>
                                                                <span class="sr-only">Next</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL END -->
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Pagination left alignment</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="example">
                                                <nav aria-label="Page navigation example">
                                                    <ul class="pagination ">
                                                        <li class="page-item disabled">
                                                            <a class="page-link" href="javascript:void(0);" tabindex="-1">
                                                                <i class="fa fa-angle-left"></i>
                                                                <span class="sr-only">Previous</span>
                                                            </a>
                                                        </li>
                                                        <li class="page-item"><a class="page-link" href="javascript:void(0);">1</a></li>
                                                        <li class="page-item active"><a class="page-link" href="javascript:void(0);">2</a></li>
                                                        <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                                                        <li class="page-item">
                                                            <a class="page-link" href="javascript:void(0);">
                                                                <i class="fa fa-angle-right"></i>
                                                                <span class="sr-only">Next</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL END -->
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Pagination center alignment</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="example">
                                                <nav aria-label="Page navigation example">
                                                    <ul class="pagination justify-content-center">
                                                        <li class="page-item disabled">
                                                            <a class="page-link" href="javascript:void(0);" tabindex="-1">
                                                                <i class="fa fa-angle-left"></i>
                                                                <span class="sr-only">Previous</span>
                                                            </a>
                                                        </li>
                                                        <li class="page-item"><a class="page-link" href="javascript:void(0);">1</a></li>
                                                        <li class="page-item active"><a class="page-link" href="javascript:void(0);">2</a></li>
                                                        <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                                                        <li class="page-item">
                                                            <a class="page-link" href="javascript:void(0);">
                                                                <i class="fa fa-angle-right"></i>
                                                                <span class="sr-only">Next</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL END -->
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Pagination Right Alignment</h3>
                                        </div>
                                        <div class="card-body ">
                                            <div class="example">
                                                <nav aria-label="Page navigation example">
                                                    <ul class="pagination justify-content-end">
                                                        <li class="page-item disabled">
                                                            <a class="page-link" href="javascript:void(0);" tabindex="-1">
                                                                <i class="fa fa-angle-left"></i>
                                                                <span class="sr-only">Previous</span>
                                                            </a>
                                                        </li>
                                                        <li class="page-item"><a class="page-link" href="javascript:void(0);">1</a></li>
                                                        <li class="page-item active"><a class="page-link" href="javascript:void(0);">2</a></li>
                                                        <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                                                        <li class="page-item">
                                                            <a class="page-link" href="javascript:void(0);">
                                                                <i class="fa fa-angle-right"></i>
                                                                <span class="sr-only">Next</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL END -->
                            </div>
                            <!-- ROW-5 CLOSED -->

@endsection

@section('scripts')



@endsection
