
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Tooltip and Popover</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Advanced Ui</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Tooltip and Popover</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW-1 OPEN -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Default Tooltip</h3>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title mt-2 fs-15">Static Example</h3>
                                            <div class="tooltip-static-demo mg-b-20">
                                                <div class="row row-sm">
                                                    <div class="col-sm-6 col-lg-3">
                                                        <div class="tooltip bs-tooltip-top" role="tooltip">
                                                            <div class="tooltip-arrow"></div>
                                                            <div class="tooltip-inner">
                                                                Tooltip on the top
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-lg-3 mg-t-30 mg-sm-t-0">
                                                        <div class="tooltip bs-tooltip-bottom" role="tooltip">
                                                            <div class="tooltip-arrow"></div>
                                                            <div class="tooltip-inner">
                                                                Tooltip on the bottom
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-lg-3 mg-t-30 mg-lg-t-0">
                                                        <div class="tooltip bs-tooltip-start" role="tooltip">
                                                            <div class="tooltip-arrow"></div>
                                                            <div class="tooltip-inner">
                                                                Tooltip on the left
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-lg-3 mg-t-30 mg-lg-t-0">
                                                        <div class="tooltip bs-tooltip-end" role="tooltip">
                                                            <div class="tooltip-arrow"></div>
                                                            <div class="tooltip-inner">
                                                                Tooltip on the right
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <h3 class="card-title mt-2 fs-15">Interactive Demo Tooltip</h3>
                                            <div class="example">
                                                <div class="row text-center">
                                                    <div class="col-md-3 mt-2 mb-2">
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip on top">Tooltip on top</button>
                                                    </div>
                                                    <div class="col-md-3 mt-2 mb-2">
                                                        <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="right" title="Tooltip on right">Tooltip on right</button>
                                                    </div>
                                                    <div class="col-md-3 mt-2 mb-2">
                                                        <button type="button" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tooltip on bottom">Tooltip on bottom</button>
                                                    </div>
                                                    <div class="col-md-3 mt-2 mb-2">
                                                        <button type="button" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="left" title="Tooltip on left">Tooltip on left</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Colored Tooltip</h3>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title mt-2 fs-15">Static Example</h3>
                                            <div class="tooltip-static-demo mg-b-20">
                                                <div class="row row-sm">
                                                    <div class="col-sm-6 col-lg-3">
                                                        <div class="tooltip tooltip-primary bs-tooltip-top" role="tooltip">
                                                            <div class="tooltip-arrow"></div>
                                                            <div class="tooltip-inner">
                                                                Tooltip on the top
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-lg-3 mg-t-30 mg-sm-t-0">
                                                        <div class="tooltip tooltip-secondary bs-tooltip-bottom" role="tooltip">
                                                            <div class="tooltip-arrow"></div>
                                                            <div class="tooltip-inner">
                                                                Tooltip on the bottom
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-lg-3 mg-t-30 mg-lg-t-0">
                                                        <div class="tooltip tooltip-info bs-tooltip-start" role="tooltip">
                                                            <div class="tooltip-arrow"></div>
                                                            <div class="tooltip-inner">
                                                                Tooltip on the left
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-lg-3 mg-t-30 mg-lg-t-0">
                                                        <div class="tooltip tooltip-danger bs-tooltip-end" role="tooltip">
                                                            <div class="tooltip-arrow"></div>
                                                            <div class="tooltip-inner">
                                                                Tooltip on the right
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <h3 class="card-title mt-2 fs-15">Interactive Demo Tooltip</h3>
                                            <div class="example">
                                                <div class="row text-center">
                                                    <div class="col-md-3 mt-2 mb-2">
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="tooltip-primary" data-bs-placement="top" title="Tooltip on top">Tooltip on top</button>
                                                    </div>
                                                    <div class="col-md-3 mt-2 mb-2">
                                                        <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip-secondary" data-bs-placement="right" title="Tooltip on right">Tooltip on right</button>
                                                    </div>
                                                    <div class="col-md-3 mt-2 mb-2">
                                                        <button type="button" class="btn btn-success" data-bs-toggle="tooltip-success" data-bs-placement="bottom" title="Tooltip on bottom">Tooltip on bottom</button>
                                                    </div>
                                                    <div class="col-md-3 mt-2 mb-2">
                                                        <button type="button" class="btn btn-info" data-bs-toggle="tooltip-info" data-bs-placement="left" title="Tooltip on left">Tooltip on left</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Default Popover</h3>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title mt-2 fs-15">Static Example</h3>
                                            <div class="example popover-static-demo mb-4">
                                                <div class="row text-center m-0 p-0">
                                                    <div class="col-md-6 mt-4">
                                                        <div class="popover bs-popover-top">
                                                            <div class="popover-arrow"></div>
                                                            <h3 class="popover-header">Popover top</h3>
                                                            <div class="popover-body">
                                                                <p class="mb-0">Vivamus sagittis lacus vel augue laoreet rutrum faucibus.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-4">
                                                        <div class="popover bs-popover-bottom">
                                                            <div class="popover-arrow"></div>
                                                            <h3 class="popover-header">Popover Bottom</h3>
                                                            <div class="popover-body">
                                                                <p class="mb-0">Vivamus sagittis lacus vel augue laoreet rutrum faucibus.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-4">
                                                        <div class="popover bs-popover-start">
                                                            <div class="popover-arrow"></div>
                                                            <h3 class="popover-header">Popover Left</h3>
                                                            <div class="popover-body">
                                                                <p class="mb-0">Vivamus sagittis lacus vel augue laoreet rutrum faucibus.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-4">
                                                        <div class="popover bs-popover-end">
                                                            <div class="popover-arrow"></div>
                                                            <h3 class="popover-header">Popover Right</h3>
                                                            <div class="popover-body">
                                                                <p class="mb-0">Vivamus sagittis lacus vel augue laoreet rutrum faucibus.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <h3 class="card-title mt-2 fs-15">Interactive Demo Tooltip</h3>
                                            <div class="example">
                                                <div class="row text-center">
                                                    <div class="col-sm-6 col-lg-3 mt-2 mb-2">
                                                        <button type="button" class="btn btn-warning" data-bs-container="body" data-bs-toggle="popover" data-bs-popover-color="default" data-bs-placement="top" title="Popover top" data-bs-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">
                                                            Popover top
                                                        </button>
                                                    </div><!-- col-3 -->
                                                    <div class="col-sm-6 col-lg-3 mt-2 mb-2">
                                                        <button type="button" class="btn btn-purple" data-bs-container="body" data-bs-toggle="popover" data-bs-popover-color="default" data-bs-placement="right" title="Popover right" data-bs-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">
                                                            Popover right
                                                        </button>
                                                    </div><!-- col-3 -->
                                                    <div class="col-sm-6 col-lg-3 mt-2 mb-2 ">
                                                        <button type="button" class="btn btn-danger" data-bs-container="body" data-bs-toggle="popover" data-bs-popover-color="default" data-bs-placement="bottom" title="Popover bottom" data-bs-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">
                                                            Popover bottom
                                                        </button>
                                                    </div><!-- col-3 -->
                                                    <div class="col-sm-6 col-lg-3 mt-2 mb-2">
                                                        <button type="button" class="btn btn-cyan" data-bs-container="body" data-bs-toggle="popover" data-bs-popover-color="default" data-bs-placement="left" title="Popover left" data-bs-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">
                                                            Popover left
                                                        </button>
                                                    </div><!-- col-3 -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Colored Head Popover</h3>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title mt-2 fs-15">Static Example</h3>
                                            <div class="example popover-static-demo mb-4">
                                                <div class="row text-center m-0 p-0">
                                                    <div class="col-md-6 mt-4">
                                                        <div class="popover popover-head-primary bs-popover-top">
                                                            <div class="popover-arrow"></div>
                                                            <h3 class="popover-header">Popover top</h3>
                                                            <div class="popover-body">
                                                                <p>Sed posuere consectetur est at lobortis. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-4">
                                                        <div class="popover popover-head-secondary bs-popover-bottom">
                                                            <div class="popover-arrow"></div>
                                                            <h3 class="popover-header">Popover Bottom</h3>
                                                            <div class="popover-body">
                                                                <p>Sed posuere consectetur est at lobortis. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <h3 class="card-title mt-2 fs-15">Interactive Demo Tooltip</h3>
                                            <div class="example">
                                                <div class="row text-center">
                                                    <div class="col-sm-6 col-lg-3 mt-2 mb-2">
                                                        <button class="btn btn-primary" data-bs-container="body" data-bs-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus." data-bs-placement="top" data-bs-popover-color="head-primary" title="Popover top">Click me</button>
                                                    </div><!-- col-3 -->
                                                    <div class="col-sm-6 col-lg-3 mt-2 mb-2">
                                                        <button class="btn btn-secondary" data-bs-container="body" data-bs-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus." data-bs-placement="bottom" data-bs-popover-color="head-secondary" title="Popover bottom">Click me</button>
                                                    </div><!-- col-3 -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Colored Popover</h3>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title mt-2 fs-15">Static Example</h3>
                                            <div class="example popover-static-demo mb-4">
                                                <div class="row text-center m-0 p-0">
                                                    <div class="col-md-6 mt-4">
                                                        <div class="popover popover-primary bs-popover-top">
                                                            <div class="popover-arrow"></div>
                                                            <h3 class="popover-header">Popover top</h3>
                                                            <div class="popover-body">
                                                                <p>Sed posuere consectetur est at lobortis. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-4">
                                                        <div class="popover popover-secondary bs-popover-bottom">
                                                            <div class="popover-arrow"></div>
                                                            <h3 class="popover-header">Popover Bottom</h3>
                                                            <div class="popover-body">
                                                                <p>Sed posuere consectetur est at lobortis. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <h3 class="card-title mt-2 fs-15">Interactive Demo Tooltip</h3>
                                            <div class="example">
                                                <div class="row text-center">
                                                    <div class="col-sm-6 col-lg-3 mt-2 mb-2">
                                                        <button class="btn ripple btn-primary" data-bs-container="body"
                                                            data-bs-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus."
                                                            data-bs-placement="top" data-bs-popover-color="primary"
                                                            title="Popover top" type="button">Click me</button>
                                                    </div><!-- col-3 -->
                                                    <div class="col-sm-6 col-lg-3 mt-2 mb-2">
                                                        <button class="btn ripple btn-secondary" data-bs-container="body"
                                                            data-bs-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus."
                                                            data-bs-placement="bottom" data-bs-popover-color="secondary"
                                                            title="Popover bottom" type="button">Click me</button>
                                                    </div><!-- col-3 -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                            </div>
                            <!-- ROW-1 CLOSED -->

@endsection

@section('scripts')

        <!-- Tooltip js-->
		@vite('resources/assets/js/tooltip.js')

        <!-- POPOVER JS -->
		@vite('resources/assets/js/popover.js')

@endsection
