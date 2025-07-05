
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Blog Details</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Pages</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Blog Details</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW-1 OPEN -->
                            <div class="row">
                                <div class="col-xl-8 blog-details">
                                    <div class="card">
                                        <img class="card-img-top " src="{{asset('build/assets/images/media/14.jpg')}}" alt="Card image cap">
                                        <div class="card-body">
                                            <div class="d-md-flex">
                                                <a href="javascript:void(0);" class="d-flex me-4 mb-2"><i class="fe fe-calendar fs-16 me-1 p-3 bg-secondary-transparent text-secondary br-5"></i>
                                                    <div class="mt-0 mt-3 ms-1 text-muted fw-semibold">Sep-25-2021</div>
                                                </a>
                                                <a href="{{url('profile')}}" class="d-flex mb-2"><i class="fe fe-user fs-16 me-1 p-3 bg-primary-transparent text-primary br-5"></i>
                                                    <div class="mt-0 mt-3 ms-1 text-muted fw-semibold">Harry Fisher</div>
                                                </a>
                                                <div class="ms-auto">
                                                    <a href="javascript:void(0);" class="d-flex mb-2"><i class="fe fe-message-square fs-16 me-1 p-3 bg-success-transparent text-success br-5"></i>
                                                        <div class="mt-0 mt-3 ms-1 text-muted fw-semibold">13 Comments</div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h3><a href="javascript:void(0);"> Voluptatem quia voluptas</a></h3>
                                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                            <p> Lorem ipsum dolor sit amet, quis Neque porro quisquam est, nostrud exercitation ullamco laboris commodo consequat. There’s an old maxim that states, “No fun for the writer, no fun for the reader.”No matter what industry
                                                you’re working in, as a blogger, you should live and die by this statement.</p>
                                            <p>I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the
                                                truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure.</p>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Comments</div>
                                        </div>
                                        <div class="card-body pb-0">
                                            <div class="media mb-5 d-block d-sm-flex">
                                                <div class="me-3 mb-2">
                                                    <a href="{{url('profile')}}"> <img class="media-object rounded-circle thumb-sm" alt="64x64" src="{{asset('build/assets/images/users/5.jpg')}}"> </a>
                                                </div>
                                                <div class="media-body">
                                                    <div class="border mb-5 p-4 br-5">
                                                        <nav class="nav float-end">
                                                            <div class="dropdown">
                                                                <a class="nav-link text-muted fs-16 p-0 ps-4" href="javascript:;" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-edit mx-1 d-inline-flex"></i> Edit</a>
                                                                    <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-corner-up-left mx-1 d-inline-flex"></i> Reply</a>
                                                                    <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-flag mx-1 d-inline-flex"></i> Report Abuse</a>
                                                                    <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-trash-2 mx-1 d-inline-flex"></i> Delete</a>
                                                                </div>
                                                            </div>
                                                        </nav>
                                                        <h5 class="mt-0">Gavin Murray</h5>
                                                        <span><i class="fe fe-thumb-up text-danger"></i></span>
                                                        <p class="fs-13 text-muted">Lorem ipsum dolor sit amet, quis Neque porro quisquam est, nostrud exercitation ullamco laboris commodo consequat. There’s an old maxim that states, “No fun for the writer, no fun for the reader.”No matter
                                                            what industry you’re working in, as a blogger, you should live and die by this statement.</p>
                                                        <a class="like" href="javascript:;">
                                                            <span class="badge btn-danger-light rounded-pill py-2 px-3">
                                                                <i class="fe fe-heart me-1"></i>56</span>
                                                        </a>
                                                        <span class="reply" id="1">
                                                            <a href="javascript:;"><span class="badge btn-primary-light rounded-pill py-2 px-3"><i class="fe fe-corner-up-left mx-1"></i>Reply</span></a>
                                                        </span>
                                                    </div>
                                                    <div class="media mb-5">
                                                        <div class="me-3">
                                                            <a href="{{url('profile')}}"> <img class="media-object rounded-circle thumb-sm" alt="64x64" src="{{asset('build/assets/images/users/2.jpg')}}"> </a>
                                                        </div>
                                                        <div class="media-body border p-4 br-5">
                                                            <nav class="nav float-end">
                                                                <div class="dropdown">
                                                                    <a class="nav-link text-muted fs-16 p-0 ps-4" href="javascript:;" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-edit mx-1 d-inline-flex"></i> Edit</a>
                                                                        <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-corner-up-left mx-1 d-inline-flex"></i> Reply</a>
                                                                        <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-flag mx-1 d-inline-flex"></i> Report Abuse</a>
                                                                        <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-trash-2 mx-1 d-inline-flex"></i> Delete</a>
                                                                    </div>
                                                                </div>
                                                            </nav>
                                                            <h5 class="mt-0">Mozelle Belt</h5>
                                                            <span><i class="fe fe-thumb-up text-danger"></i></span>
                                                            <p class="fs-13 text-muted">Nostrud exercitation ullamco laboris commodo consequat. There’s an old maxim that states, “No fun for the writer, no fun for the reader.”No matter what industry you’re working in, as a blogger, you should
                                                                live and die by this statement.</p>
                                                            <a class="like" href="javascript:;"><span class="badge btn-danger-light rounded-pill py-2 px-3"><i class="fe fe-heart me-1"></i>56</span></a>
                                                            <span class="reply" id="2">
                                                                <a href="javascript:;"><span class="badge btn-primary-light rounded-pill py-2 px-3"><i class="fe fe-corner-up-left mx-1"></i>Reply</span></a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="media mb-5">
                                                <div class="me-3">
                                                    <a href="{{url('profile')}}"> <img class="media-object rounded-circle thumb-sm" alt="64x64" src="{{asset('build/assets/images/users/15.jpg')}}"> </a>
                                                </div>
                                                <div class="media-body">
                                                    <div class="border mb-5 p-4 br-5">
                                                        <nav class="nav float-end">
                                                            <div class="dropdown">
                                                                <a class="nav-link text-muted fs-16 p-0 ps-4" href="javascript:;" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-edit mx-1 d-inline-flex"></i> Edit</a>
                                                                    <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-corner-up-left mx-1 d-inline-flex"></i> Reply</a>
                                                                    <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-flag mx-1 d-inline-flex"></i> Report Abuse</a>
                                                                    <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-trash-2 mx-1 d-inline-flex"></i> Delete</a>
                                                                </div>
                                                            </div>
                                                        </nav>
                                                        <h5 class="mt-0">Paul Smith</h5>
                                                        <p class="fs-13 text-muted">Very nice ! On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the </p>
                                                        <a class="like" href="javascript:;"><span class="badge btn-danger-light rounded-pill py-2 px-3"><i class="fe fe-heart me-1"></i>56</span></a>
                                                        <span class="reply" id="3">
                                                            <a href="javascript:;"><span class="badge btn-primary-light rounded-pill py-2 px-3"><i class="fe fe-corner-up-left mx-1"></i>Reply</span></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Add a Comments</div>
                                        </div>
                                        <div class="card-body">
                                            <form class="form-horizontal  m-t-20" action="{{url('index')}}">
                                                <div class="form-group">
                                                    <div class="col-xs-12">
                                                        <input class="form-control" type="text" required="" placeholder="Username*">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-12">
                                                        <input class="form-control" type="email" required="" placeholder="Email*">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-12">
                                                        <textarea class="form-control" rows="5">Your Comment*</textarea>
                                                    </div>
                                                </div>
                                                <div class="">
                                                    <a href="javascript:void(0);" class="btn btn-primary btn-rounded  waves-effect waves-light">Submit</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="input-group">
                                                <input type="text" class="form-control border-end-0" placeholder="Search ...">
                                                <button class="btn btn-primary border-start-0 text-white">
                                                        <i class="fe fe-search" aria-hidden="true"></i>
                                                    </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Categories</div>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group">
                                                    <li class="list-group-item border-0 p-0"><a href="javascript:void(0);"><i class="fe fe-chevron-right d-inline-flex"></i> Development</a> <span class="product-label">22</span> </li>
                                                    <li class="list-group-item border-0 p-0"><a href="javascript:void(0);"><i class="fe fe-chevron-right d-inline-flex"></i> Web Design</a> <span class="product-label">15</span> </li>
                                                    <li class="list-group-item border-0 p-0"><a href="javascript:void(0);"><i class="fe fe-chevron-right d-inline-flex"></i> Technology</a> <span class="product-label">10</span> </li>
                                                    <li class="list-group-item border-0 p-0"><a href="javascript:void(0);"><i class="fe fe-chevron-right d-inline-flex"></i> Sports</a> <span class="product-label">88</span> </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Professional Blog Writers</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="">
                                                <div class="d-flex">
                                                    <img class="avatar br-5 avatar-xl me-3" src="{{asset('build/assets/images/users/1.jpg')}}" alt="avatar-img">
                                                    <div class="media-body valign-middle">
                                                        <a href="{{url('profile')}}" class="fw-semibold text-dark">Simon Sais</a>
                                                        <p class="text-muted mb-0">There are many variations of passages of Lorem Ipsum available ...</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex mt-5">
                                                    <img class="avatar br-5 avatar-xl me-3" src="{{asset('build/assets/images/users/3.jpg')}}" alt="avatar-img">
                                                    <div class="media-body valign-middle">
                                                        <a href="{{url('profile')}}" class="fw-semibold text-dark">Cherry Blossom</a>
                                                        <p class="text-muted mb-0">There are many variations of passages of Lorem Ipsum available ...</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex mt-5">
                                                    <img class="avatar br-5 avatar-xl me-3" src="{{asset('build/assets/images/users/5.jpg')}}" alt="avatar-img">
                                                    <div class="media-body valign-middle">
                                                        <a href="{{url('profile')}}" class="fw-semibold text-dark">Ivan Notheridiya</a>
                                                        <p class="text-muted mb-0">There are many variations of passages of Lorem Ipsum available ...</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex mt-5">
                                                    <img class="avatar br-5 avatar-xl me-3" src="{{asset('build/assets/images/users/15.jpg')}}" alt="avatar-img">
                                                    <div class="media-body valign-middle">
                                                        <a href="{{url('profile')}}" class="fw-semibold text-dark">Manny Jah</a>
                                                        <p class="text-muted mb-0">There are many variations of passages of Lorem Ipsum available ...</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card blog-recent">
                                        <div class="card-header">
                                            <div class="card-title">Recent Posts</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="">
                                                <div class="d-flex">
                                                    <img class="avatar br-5 avatar-xl me-3" src="{{asset('build/assets/images/media/26.jpg')}}" alt="avatar-img">
                                                    <div class="media-body valign-middle">
                                                        <a href="{{url('profile')}}" class="fw-semibold text-dark">Generator on the Internet.</a>
                                                        <p class="text-muted mb-0">There are many variations of passages of Lorem Ipsum available ...</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex mt-5">
                                                    <img class="avatar br-5 avatar-xl me-3" src="{{asset('build/assets/images/media/21.jpg')}}" alt="avatar-img">
                                                    <div class="media-body valign-middle">
                                                        <a href="{{url('profile')}}" class="fw-semibold text-dark">Voluptatem quia voluptas...</a>
                                                        <p class="text-muted mb-0">There are many variations of passages of Lorem Ipsum available ...</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex mt-5">
                                                    <img class="avatar br-5 avatar-xl me-3" src="{{asset('build/assets/images/media/13.jpg')}}" alt="avatar-img">
                                                    <div class="media-body valign-middle">
                                                        <a href="{{url('profile')}}" class="fw-semibold text-dark">Generator on the Internet.</a>
                                                        <p class="text-muted mb-0">There are many variations of passages of Lorem Ipsum available ...</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex mt-5">
                                                    <img class="avatar br-5 avatar-xl me-3" src="{{asset('build/assets/images/media/15.jpg')}}" alt="avatar-img">
                                                    <div class="media-body valign-middle">
                                                        <a href="{{url('profile')}}" class="fw-semibold text-dark">Voluptatem quia voluptas...</a>
                                                        <p class="text-muted mb-0">There are many variations of passages of Lorem Ipsum available ...</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Tags</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="tags">
                                                <a href="javascript:void(0);" class="tag">Development</a>
                                                <a href="javascript:void(0);" class="tag">Design</a>
                                                <a href="javascript:void(0);" class="tag">Technology</a>
                                                <a href="javascript:void(0);" class="tag">Popular</a>
                                                <a href="javascript:void(0);" class="tag">Codiegniter</a>
                                                <a href="javascript:void(0);" class="tag">JavaScript</a>
                                                <a href="javascript:void(0);" class="tag">Bootstrap</a>
                                                <a href="javascript:void(0);" class="tag">PHP</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-1 CLOSED -->

@endsection

@section('scripts')



@endsection
