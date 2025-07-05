
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Modal</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Advanced Ui</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Modal</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- Row -->
                            <div class="card" id="modal5">
                                <div class="card-header border-bottom-0">
                                    <div class="card-title">
                                        Modal Animation Effects
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row ">
                                        <div class="col-sm-6 col-md-6 col-xl-3">
                                            <a class="modal-effect btn btn-primary-light d-grid mb-3" data-bs-effect="effect-scale" data-bs-toggle="modal" href="#modaldemo8">Scale</a>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-xl-3">
                                            <a class="modal-effect btn btn-secondary-light d-grid mb-3" data-bs-effect="effect-slide-in-right" data-bs-toggle="modal" href="#modaldemo8">Slide In Right</a>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-xl-3">
                                            <a class="modal-effect btn btn-danger-light d-grid mb-3" data-bs-effect="effect-slide-in-bottom" data-bs-toggle="modal" href="#modaldemo8">Slide In Bottom</a>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-xl-3">
                                            <a class="modal-effect btn btn-warning-light d-grid mb-3" data-bs-effect="effect-newspaper" data-bs-toggle="modal" href="#modaldemo8">Newspaper</a>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-xl-3">
                                            <a class="modal-effect btn btn-success-light d-grid mb-3" data-bs-effect="effect-fall" data-bs-toggle="modal" href="#modaldemo8">Fall</a>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-xl-3">
                                            <a class="modal-effect btn btn-info-light d-grid mb-3" data-bs-effect="effect-flip-horizontal" data-bs-toggle="modal" href="#modaldemo8">Flip Horizontal</a>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-xl-3">
                                            <a class="modal-effect btn btn-primary-light d-grid mb-3" data-bs-effect="effect-flip-vertical" data-bs-toggle="modal" href="#modaldemo8">Flip Vertical</a>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-xl-3">
                                            <a class="modal-effect btn btn-secondary-light d-grid mb-3" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#modaldemo8">Super Scaled</a>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-xl-3">
                                            <a class="modal-effect btn btn-danger-light d-grid mb-3" data-bs-effect="effect-sign" data-bs-toggle="modal" href="#modaldemo8">Sign</a>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-xl-3">
                                            <a class="modal-effect btn btn-warning-light d-grid mb-3" data-bs-effect="effect-rotate-bottom" data-bs-toggle="modal" href="#modaldemo8">Rotate Bottom</a>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-xl-3">
                                            <a class="modal-effect btn btn-success-light d-grid mb-3" data-bs-effect="effect-rotate-left" data-bs-toggle="modal" href="#modaldemo8">Rotate Left</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Row -->

                            <!-- Row -->
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <div class="card">
                                        <div class="card-header border-bottom-0">
                                            <h3 class="card-title">Modal Sizes & Types</h3>
                                        </div>
                                        <div class="card-body text-center">
                                            <p>Add <code class="highlighter-rouge">.modal-sm </code> or <code class="highlighter-rouge">.modal-lg </code> or <code class="highlighter-rouge">.modal-xl </code> to modal-dialog to increase and decrease the modal box
                                                sizes.
                                            </p>
                                            <button class="btn btn-primary bg-primary-gradient mt-2" data-bs-toggle="modal" data-bs-target="#smallmodal">Small Modal</button>
                                            <button class="btn btn-success bg-success-gradient mt-2" data-bs-toggle="modal" data-bs-target="#largemodal">large Modal</button>
                                            <button class="btn btn-secondary bg-secondary-gradient mt-2" data-bs-toggle="modal" data-bs-target="#extralargemodal">Extralarge Modal</button>
                                            <button class="btn btn-danger bg-danger-gradient mt-2" data-bs-toggle="modal" data-bs-target="#scrollingmodal">Scrolling Modal</button>
                                            <button class="btn btn-warning bg-warning-gradient mt-2" data-bs-toggle="modal" data-bs-target="#fullscreenmodal">Fullscreen Modal</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Row -->

                            <!-- Row -->
                            <div class="row row-sm">
                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="card custom-card">
                                        <div class="card-body">
                                            <div>
                                                <h6 class="main-content-label mb-3">Select2 Inside Modals</h6>
                                            </div>
                                            <a class="btn btn-pink me-3  mt-2" data-bs-target="#select2modal" data-bs-toggle="modal" href="javascript:;">Select2 Modal </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="card custom-card">
                                        <div class="card-body">
                                            <div>
                                                <h6 class="main-content-label mb-3">Input Modal</h6>
                                            </div>
                                            <button type="button" class="btn btn-green me-3  mt-2" data-bs-toggle="modal" data-bs-target="#input-modal" data-bs-whatever="@mdo">Open modal for @mdo</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Row -->

                            <!-- Row -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <!--div-->
                                    <div class="card" id="modal">
                                        <div class="card-header border-bottom-0">
                                            <div class="card-title">
                                                Basic Modal
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="p-4 bg-light border border-bottom-0">
                                                <div class="modal d-block pos-static">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content modal-content-demo">
                                                            <div class="modal-header">
                                                                <h6 class="modal-title">Message Preview</h6>
                                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h6>Why We Use Electoral College, Not Popular Vote</h6>
                                                                <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution
                                                                    of letters, as opposed to using 'Content here, content here', making it look like readable English.</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-primary">Save changes</button> 
                                                                <button class="btn btn-light">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- pd-y-50 -->
                                            <div class="text-center py-4 bg-light border">
                                                <a class="btn btn-primary" data-bs-target="#modaldemo1" data-bs-toggle="modal" href="javascript:void(0);">View Live Demo</a>
                                            </div>
                                            <!-- pd-y-30 -->
                                        </div>
                                    </div>
                                    <!--/div-->
                                    <!--div-->
                                    <div class="card" id="modal3">
                                        <div class="card-header border-bottom-0">
                                            <div class="card-title">
                                                Success Alert Messages
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="p-4 bg-light border border-bottom-0">
                                                <div class="modal d-block pos-static">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-body text-center p-4 pb-5">
                                                                <button aria-label="Close" class="btn-close position-absolute" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                                                                <i class="icon icon-check fs-70 text-success lh-1 my-4 d-inline-block"></i>
                                                                <h4 class="text-success mb-4">Congratulations!</h4>
                                                                <p class="mb-4 mx-4">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.</p><button class="btn btn-success pd-x-25">Continue</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- modal-wrapper-demo -->
                                            <div class="text-center py-4 bg-light border">
                                                <a class="btn btn-primary" data-bs-target="#modaldemo4" data-bs-toggle="modal" href="javascript:void(0);">View Live Demo</a>
                                            </div>
                                            <!-- modal-footer-demo -->
                                        </div>
                                    </div>
                                    <!--/div-->
                                    <!--div-->
                                    <div class="card" id="modal4">
                                        <div class="card-header border-bottom-0">
                                            <div class="card-title">
                                                Warning Alert Messages
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="p-4 bg-light border border-bottom-0 mg-t-20">
                                                <div class="modal d-block pos-static">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-body text-center p-4 pb-5">
                                                                <button aria-label="Close" class="btn-close position-absolute" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                                                                <i class="icon icon-close fs-70 text-danger lh-1 my-4 d-inline-block"></i>
                                                                <h4 class="text-danger mb-20">Error: Cannot process your entry!</h4>
                                                                <p class="mb-4 mx-4">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.</p><button aria-label="Close" class="btn btn-danger pd-x-25">Continue</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- modal-wrapper-demo -->
                                            <div class="text-center py-4 bg-light border">
                                                <a class="btn btn-primary" data-bs-target="#modaldemo5" data-bs-toggle="modal" href="javascript:void(0);">View Live Demo</a>
                                            </div>
                                            <!-- modal-footer-demo -->
                                        </div>
                                    </div>
                                    <!--/div-->


                                </div>
                            </div>
                            <!-- End Row -->

                            <!-- BASIC MODAL -->
                            <div class="modal fade" id="modaldemo1">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content modal-content-demo">
                                        <div class="modal-header">
                                            <h6 class="modal-title">Message Preview</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <h6>Why We Use Electoral College, Not Popular Vote</h6>
                                            <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to
                                                using 'Content here, content here', making it look like readable English.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary">Save changes</button> <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SMALL MODAL -->
                            <div class="modal fade" id="modaldemo2">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title">Notice</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button class="btn btn-primary">Save changes</button> <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- LARGE MODAL -->
                            <div class="modal fade" id="modaldemo3">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content modal-content-demo">
                                        <div class="modal-header">
                                            <h6 class="modal-title">Message Preview</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <h6>Why We Use Electoral College, Not Popular Vote</h6>
                                            <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to
                                                using 'Content here, content here', making it look like readable English.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary">Save changes</button> <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MODAL ALERT MESSAGE -->
                            <div class="modal fade" id="modaldemo4">
                                <div class="modal-dialog modal-dialog-centered text-center " role="document">
                                    <div class="modal-content tx-size-sm">
                                        <div class="modal-body text-center p-4 pb-5">
                                            <button aria-label="Close" class="btn-close position-absolute" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                                            <i class="icon icon-check fs-70 text-success lh-1 my-5 d-inline-block"></i>
                                            <h4 class="text-success tx-semibold">Congratulations!</h4>
                                            <p class="mg-b-20 mg-x-20">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.</p><button aria-label="Close" class="btn btn-success pd-x-25" data-bs-dismiss="modal">Continue</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="modaldemo5">
                                <div class="modal-dialog modal-dialog-centered text-center" role="document">
                                    <div class="modal-content tx-size-sm">
                                        <div class="modal-body text-center p-4 pb-5">
                                            <button aria-label="Close" class="btn-close position-absolute" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                                            <i class="icon icon-close fs-70 text-danger lh-1 my-5 d-inline-block"></i>
                                            <h4 class="text-danger">Error: Cannot process your entry!</h4>
                                            <p class="mg-b-20 mg-x-20">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.</p><button aria-label="Close" class="btn btn-danger pd-x-25" data-bs-dismiss="modal">Continue</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MODAL EFFECTS -->
                            <div class="modal fade" id="modaldemo8">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content modal-content-demo">
                                        <div class="modal-header">
                                            <h6 class="modal-title">Message Preview</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <h6>Why We Use Electoral College, Not Popular Vote</h6>
                                            <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to
                                                using 'Content here, content here', making it look like readable English.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary">Save changes</button> <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal  fade" id="smallmodal" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Modal title</h5>
                                            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Modal body text goes here.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="largemodal" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg " role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Modal title</h5>
                                            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Modal body text goes here.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="extralargemodal" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Modal title</h5>
                                            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Modal body text goes here.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="scrollingmodal" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Modal title</h5>
                                            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum..</p>
                                            <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder
                                                of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is
                                                there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which
                                                of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain
                                                that produces no resultant pleasure?</p>
                                            <p>On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound
                                                to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is the same as saying through shrinking from toil and pain. These cases are perfectly simple and easy to distinguish. In a free hour,
                                                when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. But in certain circumstances and owing to the claims of duty or
                                                the obligations of business it will frequently occur that pleasures have to be repudiated and annoyances accepted. The wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure
                                                other greater pleasures, or else he endures pains to avoid worse pains.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="fullscreenmodal" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-fullscreen" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Modal title</h5>
                                            <button class="btn-close me-1" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Modal body text goes here.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Select2 modal -->
                            <div class="modal fade" id="select2modal">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content modal-content-demo">
                                        <div class="modal-header">
                                            <h6 class="modal-title">Select2 Modal</h6>
                                            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h6>Modal Body</h6>
                                            <!-- Select2 -->
                                            <select class="form-control select2 select2-dropdown">
                                                <option label="Choose one">
                                                Choose one
                                                </option>
                                                <option value="Firefox">
                                                Firefox
                                                </option>
                                                <option value="Chrome">
                                                Chrome
                                                </option>
                                                <option value="Safari">
                                                Safari
                                                </option>
                                                <option value="Opera">
                                                Opera
                                                </option>
                                                <option value="Internet Explorer">
                                                Internet Explorer
                                                </option>
                                            </select>
                                            <!-- Select2 -->
                                            <p class="mt-3">Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn ripple btn-success" type="button">Save changes</button>
                                            <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Select2 modal -->

                            <!-- Inout modal -->
                            <div class="modal fade" id="input-modal">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content modal-content-demo">
                                        <div class="modal-header">
                                            <h6 class="modal-title">New message to @mdo</h6>
                                            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form>
                                            <div class="mb-3">
                                                <label for="recipient-name" class="col-form-label">Recipient:</label>
                                                <input type="text" class="form-control" id="recipient-name">
                                            </div>
                                            <div class="mb-3">
                                                <label for="message-text" class="col-form-label">Message:</label>
                                                <textarea class="form-control" id="message-text"></textarea>
                                            </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn ripple btn-success" type="button">Save changes</button>
                                            <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Datepicker modal -->

@endsection

@section('scripts')

		<!-- SELECT2 JS -->
		<script src="{{asset('build/assets/plugins/select2/select2.full.min.js')}}"></script>
		@vite('resources/assets/js/select2.js')

		<!-- Modal js-->
		@vite('resources/assets/js/modal.js')

@endsection
