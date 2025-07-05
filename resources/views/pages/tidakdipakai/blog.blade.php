
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Blog</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Pages</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Blog</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW-1 OPEN -->
                            <div class="row row-cards row-deck">
                                <div class="col-md-4">
                                    <div class="card">
                                        <img class="card-img-top br-tr-7 br-tl-7" src="{{asset('build/assets/images/media/19.jpg')}}" alt="Card image cap">
                                        <div class="card-header">
                                            <h5 class="card-title">Blog title </h5>
                                        </div>
                                        <div class="card-body">
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                        <a href="javascript:void(0);" class="float-end">Read more <i class="fa fa-angle-double-right"></i></a>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-md-4">
                                    <div class="card">
                                        <img class="card-img-top br-tr-7 br-tl-7" src="{{asset('build/assets/images/media/12.jpg')}}" alt="Card image cap">
                                        <div class="card-header">
                                        <h5 class="card-title">Blog title</h5>
                                        </div>
                                        <div class="card-body">
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                        <a href="javascript:void(0);" class="float-end text-info">Read more <i class="fa fa-angle-double-right"></i></a>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-md-4">
                                    <div class="card ">
                                        <img class="card-img-top br-tr-7 br-tl-7" src="{{asset('build/assets/images/media/17.jpg')}}" alt="Card image cap">
                                        <div class="card-header">
                                        <h5 class="card-title">Blog title</h5>
                                        </div>
                                        <div class="card-body">
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                        <a href="javascript:void(0);" class="float-end text-success">Read more <i class="fa fa-angle-double-right"></i></a>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-md-4">
                                    <div class="card">
                                        <img class="card-img-top br-tr-7 br-tl-7 " src="{{asset('build/assets/images/media/20.jpg')}}" alt="Card image cap">
                                        <div class="card-header">
                                            <h5 class="card-title">Blog title</h5>
                                        </div>
                                        <div class="card-body">
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                        <a href="" class="btn btn-primary btn-md">Read more</a>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-md-4">
                                    <div class="card ">
                                        <img class="card-img-top br-tr-7 br-tl-7 " src="{{asset('build/assets/images/media/23.jpg')}}" alt="Card image cap">
                                        <div class="card-header">
                                            <h5 class="card-title">Blog title</h5>
                                        </div>
                                        <div class="card-body">
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                        <a href="" class="btn btn-warning btn-md">Read more</a>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-md-4">
                                    <div class="card ">
                                        <img class="card-img-top br-tr-7 br-tl-7 " src="{{asset('build/assets/images/media/22.jpg')}}" alt="Card image cap">
                                        <div class="card-header">
                                            <h5 class="card-title">Blog title</h5>
                                        </div>
                                        <div class="card-body">
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                        <a href="" class="btn btn-info btn-md">Read more</a>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-sm-6 col-xl-3">
                                    <div class="card">
                                        <a href="javascript:void(0);"><img class="card-img-top br-tr-7 br-tl-7" src="{{asset('build/assets/images/media/13.jpg')}}" alt="And this isn&#39;t my nose. This is a false one."></a>
                                        <div class="card-body d-flex flex-column">
                                            <h4><a href="javascript:void(0);">voluptatem quia voluptas.</a></h4>
                                            <div class="text-muted">To take a trivial example, which of us ever undertakes laborious physical exerciser , except to obtain some advantage from it...</div>
                                            <div class="d-flex align-items-center pt-5 mt-auto">
                                                <div class="avatar brround avatar-md me-3 cover-image" data-bs-image-src="{{asset('build/assets/images/users/18.jpg')}}"></div>
                                                <div>
                                                    <a href="{{url('profile')}}" class="text-default">Megan Peters</a>
                                                    <small class="d-block text-muted">1 days ago</small>
                                                </div>
                                                <div class="ms-auto text-muted">
                                                    <a href="javascript:void(0);" class="icon d-none d-md-inline-block ms-3"><i class="fe fe-heart me-1"></i></a>
                                                    <a href="javascript:void(0);" class="icon d-none d-md-inline-block ms-3"><i class="fa fa-thumbs-o-up"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-sm-6 col-xl-3">
                                    <div class="card">
                                        <a href="javascript:void(0);"><img class="card-img-top br-tr-7 br-tl-7" src="{{asset('build/assets/images/media/14.jpg')}}" alt="Well, I didn&#39;t vote for you."></a>
                                        <div class="card-body d-flex flex-column">
                                            <h4><a href="javascript:void(0);">voluptatem quia voluptas.</a></h4>
                                            <div class="text-muted">Who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces.</div>
                                            <div class="d-flex align-items-center pt-5 mt-auto">
                                                <div class="avatar brround avatar-md me-3 cover-image" data-bs-image-src="{{asset('build/assets/images/users/16.jpg')}}"></div>
                                                <div>
                                                    <a href="{{url('profile')}}" class="text-default">Anna Ogden</a>
                                                    <small class="d-block text-muted">2 days ago</small>
                                                </div>
                                                <div class="ms-auto text-muted">
                                                    <a href="javascript:void(0);" class="icon d-none d-md-inline-block ms-3"><i class="fe fe-heart me-1"></i></a>
                                                    <a href="javascript:void(0);" class="icon d-none d-md-inline-block ms-3"><i class="fa fa-thumbs-o-up"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-sm-6 col-xl-3">
                                    <div class="card">
                                        <div class="card-body d-flex flex-column">
                                            <h4><a href="javascript:void(0);">voluptatem quia voluptas</a></h4>
                                            <div class="text-muted">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque...</div>
                                            <div class="d-flex align-items-center pt-5 mt-auto">
                                                <div class="avatar  brround avatar-md me-3 cover-image" data-bs-image-src="{{asset('build/assets/images/users/6.jpg')}}"></div>
                                                <div>
                                                    <a href="{{url('profile')}}" class="text-default">Carol Paige</a>
                                                    <small class="d-block text-muted">2 days ago</small>
                                                </div>
                                                <div class="ms-auto text-muted">
                                                    <a href="javascript:void(0);" class="icon d-none d-md-inline-block ms-3"><i class="fe fe-heart me-1"></i></a>
                                                    <a href="javascript:void(0);" class="icon d-none d-md-inline-block ms-3"><i class="fa fa-thumbs-o-up"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="javascript:void(0);"><img class="card-img-top br-tl-0 br-tr-0 br-br-7 br-bl-7" src="{{asset('build/assets/images/media/15.jpg')}}" alt="How do you know she is a witch?"></a>
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-sm-6 col-xl-3">
                                    <div class="card">
                                        <div class="card-body d-flex flex-column">
                                            <h4><a href="javascript:void(0);">voluptatem quia voluptas..</a></h4>
                                            <div class="text-muted">Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut ..</div>
                                            <div class="d-flex align-items-center pt-5 mt-auto">
                                                <div class="avatar brround avatar-md me-3 cover-image" data-bs-image-src="{{asset('build/assets/images/users/7.jpg')}}"></div>
                                                <div>
                                                    <a href="{{url('profile')}}" class="text-default">Fiona Hodges</a>
                                                    <small class="d-block text-muted">5 days ago</small>
                                                </div>
                                                <div class="ms-auto text-muted">
                                                    <a href="javascript:void(0);" class="icon d-none d-md-inline-block ms-3"><i class="fe fe-heart me-1"></i></a>
                                                    <a href="javascript:void(0);" class="icon d-none d-md-inline-block ms-3"><i class="fa fa-thumbs-o-up"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="javascript:void(0);"><img class="card-img-top br-tl-0 br-tr-0 br-br-7 br-bl-7" src="{{asset('build/assets/images/media/16.jpg')}}" alt="Shut up!"></a>
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-xl-12 col-xxl-6">
                                    <div class="card border-0 card-image-on-left overflow-hidden">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <img src="{{asset('build/assets/images/media/18.jpg')}}" class="card-img-start" alt="img">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body pb-0">
                                                    <p class="card-title">Card Image On Left</p>
                                                    <p class="mb-0">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                                    <p class="mb-0"><small class="text-muted">Last updated 3 mins ago</small></p>
                                                </div>
                                                <div class="card-footer border-top-0 bg-transparent">
                                                    <div class="d-flex align-items-center pt-3 mt-auto">
                                                        <div class="main-img-user avatar-sm me-3">
                                                            <img src="{{asset('build/assets/images/users/11.jpg')}}" class="w-10 rounded-circle" alt="avatar-img">
                                                        </div>
                                                        <div>
                                                            <a href="{{url('profile')}}" class="text-primary tx-semibold">Alica Nestle</a>
                                                            <small class="d-block text-muted">15 mintues ago</small>
                                                        </div>
                                                        <div class="ms-auto text-muted">
                                                            <a href="javascript:void(0);" class="icon d-none d-md-inline-block ms-3 text-primary"><i class="fe fe-heart me-1"></i></a>
                                                            <a href="javascript:void(0);" class="icon d-none d-md-inline-block ms-3 text-primary"><i class="fa fa-thumbs-o-up"></i></a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-xxl-6">
                                    <div class="card border-0 card-image-on-right overflow-hidden">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="card-body pb-0">
                                                    <p class="card-title">Card Image On Left</p>
                                                    <p class="mb-0">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                                    <p class="mb-0"><small class="text-muted">Last updated 3 mins ago</small></p>
                                                </div>
                                                <div class="card-footer border-top-0 bg-transparent">
                                                    <div class="d-flex align-items-center pt-3 mt-auto">
                                                        <div class="main-img-user avatar-sm me-3">
                                                            <img src="{{asset('build/assets/images/users/1.jpg')}}" class="w-10 rounded-circle" alt="avatar-img">
                                                        </div>
                                                        <div>
                                                            <a href="{{url('profile')}}" class="text-primary tx-semibold">Alica Nestle</a>
                                                            <small class="d-block text-muted">15 mintues ago</small>
                                                        </div>
                                                        <div class="ms-auto text-muted">
                                                            <a href="javascript:void(0);" class="icon d-none d-md-inline-block ms-3 text-primary"><i class="fe fe-heart me-1"></i></a>
                                                            <a href="javascript:void(0);" class="icon d-none d-md-inline-block ms-3 text-primary"><i class="fa fa-thumbs-o-up"></i></a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <img src="{{asset('build/assets/images/media/15.jpg')}}" class="card-img-end" alt="img">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-1 CLOSED -->

@endsection

@section('scripts')



@endsection
