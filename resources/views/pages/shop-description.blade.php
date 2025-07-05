
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Product Details</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">E-Commerce</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Product Details</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW-1 OPEN -->
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xl-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="product-carousel">
                                                        <div id="Slider" class="carousel slide border br-5" data-bs-ride="false">
                                                            <div class="carousel-inner">
                                                                <div class="carousel-item active"><img src="{{asset('build/assets/images/pngs/13.png')}}" alt="img" class="img-fluid mx-auto d-block">
                                                                    <div class="text-center mt-5 mb-5 btn-list">
                                                                    </div>
                                                                </div>
                                                                <div class="carousel-item"> <img src="{{asset('build/assets/images/pngs/12.png')}}" alt="img" class="img-fluid mx-auto d-block">
                                                                    <div class="text-center mb-5 mt-5 btn-list">
                                                                    </div>
                                                                </div>
                                                                <div class="carousel-item"> <img src="{{asset('build/assets/images/pngs/14.png')}}" alt="img" class="img-fluid mx-auto d-block">
                                                                    <div class="text-center  mb-5 mt-5 btn-list">
                                                                    </div>
                                                                </div>
                                                                <div class="carousel-item"> <img src="{{asset('build/assets/images/pngs/11.png')}}" alt="img" class="img-fluid mx-auto d-block">
                                                                    <div class="text-center  mb-5 mt-5 btn-list">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix carousel-slider">
                                                        <div id="thumbcarousel" class="carousel slide" data-bs-interval="t">
                                                            <div class="carousel-inner">
                                                                <ul class="carousel-item active">
                                                                    <li data-bs-target="#Slider" data-bs-slide-to="0" class="thumb active m-2"><img src="{{asset('build/assets/images/pngs/13.png')}}" alt="img"></li>
                                                                    <li data-bs-target="#Slider" data-bs-slide-to="1" class="thumb m-2"><img src="{{asset('build/assets/images/pngs/12.png')}}" alt="img"></li>
                                                                    <li data-bs-target="#Slider" data-bs-slide-to="2" class="thumb m-2"><img src="{{asset('build/assets/images/pngs/14.png')}}" alt="img"></li>
                                                                    <li data-bs-target="#Slider" data-bs-slide-to="3" class="thumb m-2"><img src="{{asset('build/assets/images/pngs/11.png')}}" alt="img"></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-lg-12 col-md-12 col-xl-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title"> Categories &amp; Fliters</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox-1" checked="">
                                                <label for="checkbox-1" class="custom-control-label">Mens</label>
                                            </div>
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox-2">
                                                <label for="checkbox-2" class="custom-control-label">Womens</label>
                                            </div>
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox-3">
                                                <label for="checkbox-3" class="custom-control-label">Kids</label>
                                            </div>
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox-4">
                                                <label for="checkbox-4" class="custom-control-label">Others</label>
                                            </div>
                                            <div class="mb-3 mt-3">
                                                <label class="form-label">Category</label>
                                                <select class="form-control select2 custom-select" data-placeholder="Choose one">
                                                    <option label="Choose one">
                                                    </option>
                                                    <option value="1">Dress</option>
                                                    <option value="2">Bags </option>
                                                    <option value="3">Coat</option>
                                                    <option value="4">Beauty</option>
                                                    <option value="5">Jeans</option>
                                                    <option value="5">Jewellery</option>
                                                    <option value="5">Electronics</option>
                                                    <option value="5">Sports</option>
                                                    <option value="5">Technology</option>
                                                    <option value="5">Watches</option>
                                                    <option value="5">Accessories</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Brand</label>
                                                <select class="form-control select2 custom-select" data-placeholder="Choose one">
                                                    <option label="Choose one">
                                                    </option>
                                                    <option value="1">White</option>
                                                    <option value="2">Black</option>
                                                    <option value="3">Red</option>
                                                    <option value="4">Green</option>
                                                    <option value="5">Blue</option>
                                                    <option value="6">Yellow</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Type</label>
                                                <select class="form-control select2 custom-select" data-placeholder="Choose one">
                                                    <option label="Choose one">
                                                    </option>
                                                    <option value="1">Extra Small</option>
                                                    <option value="2">Small</option>
                                                    <option value="3">Medium</option>
                                                    <option value="4">Large</option>
                                                    <option value="5">Extra Large</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Color</label>
                                                <select class="form-control select2 custom-select" data-placeholder="Choose one">
                                                    <option label="Choose one">
                                                    </option>
                                                    <option value="1">White</option>
                                                    <option value="2">Black</option>
                                                    <option value="3">Red</option>
                                                    <option value="4">Green</option>
                                                    <option value="5">Blue</option>
                                                    <option value="6">Yellow</option>
                                                </select>
                                            </div>
                                            <a class="btn btn-primary m-2" href="javascript:void(0);">Apply Filter</a>
                                            <a class="btn btn-danger m-2" href="javascript:void(0);">Search Now</a>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->

                                <div class="col-xl-12 col-md-12">
                                    <div class="card productdesc">
                                        <div class="card-body">
                                            <div class="panel panel-primary border br-5 p-0">
                                                <div class=" tab-menu-heading">
                                                    <div class="tabs-menu1">
                                                        <!-- Tabs -->
                                                        <ul class="nav panel-tabs">
                                                            <li><a href="#tab5" class="active" data-bs-toggle="tab">Specifications</a></li>
                                                            <li><a href="#tab6" data-bs-toggle="tab">Dimensions</a></li>
                                                            <li><a href="#tab7" data-bs-toggle="tab">Features</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="panel-body tabs-menu-body p-5">
                                                    <div class="tab-content">
                                                        <div class="tab-pane active" id="tab5">
                                                            <h4 class="mb-5 fw-bold">Description :</h4>
                                                            <h3 class="mb-3 fw-semibold">Trending Half Hands Tshirt (Best Fabric Premium made T-shirt for Casual wear.)</h3>
                                                            <p class="text-muted float-start me-3">
                                                                <span class="fa fa-star text-warning"></span>
                                                                <span class="fa fa-star text-warning"></span>
                                                                <span class="fa fa-star text-warning"></span>
                                                                <span class="fa fa-star-half-o text-warning"></span>
                                                                <span class="fa fa-star-o text-warning"></span>
                                                            </p>
                                                            <p class="text-muted mb-4">( 40 Customers Reviews) </p>
                                                            <p class="mb-3 fs-15">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident,
                                                                similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga.</p>
                                                            <p class="mb-3 fs-15">odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia.
                                                            </p>
                                                            <h4 class="mb-5 mt-3 fw-bold">Specifications :</h4>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered mb-0">
                                                                    <tr>
                                                                        <td class="fw-bold">Package Dimensions</td>
                                                                        <td> 33 x 22 x 3 cm; 450 Grams</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="fw-bold">Manufacturer</td>
                                                                        <td>Chokka Production</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="fw-bold">Item part number </td>
                                                                        <td>BNVRDMRHENFULL-Z14</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="fw-bold">Best Sellers Rank</td>
                                                                        <td> #141 in Clothing & Accessories (See Top 100 in Clothing & Accessories)</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="fw-bold">Customer Reviews</td>
                                                                        <td>
                                                                            <p class="text-muted float-start me-3">
                                                                                <span class="fa fa-star text-warning"></span>
                                                                                <span class="fa fa-star text-warning"></span>
                                                                                <span class="fa fa-star text-warning"></span>
                                                                                <span class="fa fa-star-half-o text-warning"></span>
                                                                                <span class="fa fa-star-o text-warning"></span>
                                                                                <span class="text-success fw-semibold">(2,076 ratings)</span>
                                                                            </p>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane" id="tab6">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered mb-0">
                                                                    <tr>
                                                                        <td class="fw-bold">Width</td>
                                                                        <td> 6.1 inch</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="fw-bold">Height</td>
                                                                        <td>24 inch</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="fw-bold">Depth</td>
                                                                        <td>6.1 inch</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="fw-bold">Other Dimensions</td>
                                                                        <td>15.5*15.5*24CM</td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane" id="tab7">
                                                            <ul class="">
                                                                <li><i class="fa fa-check me-3 text-success mb-5"></i>Asthetic Product - more lively movies and music</li>
                                                                <li><i class="fa fa-check me-3 text-success mb-5"></i>Long lasting 7,040 mAH battery with fast adaptive charging</li>
                                                                <li><i class="fa fa-check me-3 text-success mb-5"></i>8 MP Primary Camera, 5 MP Front Facing Camera</li>
                                                                <li><i class="fa fa-check me-3 text-success mb-5"></i>Seamless apps and gaming experience with Qualcomm Snapdragon 662 processor (4X2.0 GHz+4X1.8 GHz)</li>
                                                                <li><i class="fa fa-check me-3 text-success mb-5"></i>1 year manufacturer warranty for device and 6 months manufacturer warranty for in-box accessories</li>
                                                                <li><i class="fa fa-check me-3 text-success"></i>Customer care :1234 567 678</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Customer Services</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-xl-4">
                                                    <div class="customer-services mb-2">
                                                        <div class="icon-content">
                                                            <span><i class="bi bi-truck"></i></span>
                                                            <h5 class="fw-semibold">Delivery Info</h5>
                                                        </div>
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has.</p>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4">
                                                    <div class="customer-services mb-2">
                                                        <div class="icon-content">
                                                            <span><i class="bi bi-arrow-repeat"></i></span>
                                                            <h5 class="fw-semibold">30 days returns</h5>
                                                        </div>
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has.</p>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4">
                                                    <div class="customer-services">
                                                        <div class="icon-content">
                                                            <span><i class="bi bi-credit-card-2-front"></i></span>
                                                            <h5 class="fw-semibold">15 years Warranty</h5>
                                                        </div>
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Reviews</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="media mb-5 mt-0">
                                                <div class="me-3">
                                                    <a href="javascript:void(0);"> <img class="media-object rounded-circle thumb-sm" alt="64x64" src="{{asset('build/assets/images/users/5.jpg')}}"> </a>
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="mt-0 mb-0">Gavin Murray</h5>
                                                    <div class="text-warning mb-0 mt-1">
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-o"></i>
                                                    </div>
                                                    <p class="font-13 text-muted">Quality is Fine & Good Looking.........</p>
                                                    <a class="like" href="javascript:;">
                                                        <span class="badge btn-danger-light rounded-pill py-2 px-3">
                                                                <i class="fe fe-heart me-1"></i>56</span>
                                                    </a>
                                                    <span class="reply" id="6">
                                                            <a href="javascript:;"><span class="badge btn-primary-light rounded-pill py-2 px-3"><i class="fe fe-corner-up-left me-1"></i>Reply</span></a>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="media mb-0">
                                                <div class=" me-3">
                                                    <a href="javascript:void(0);"> <img class="media-object rounded-circle thumb-sm" alt="64x64" src="{{asset('build/assets/images/users/15.jpg')}}"> </a>
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="mt-0 mb-0">Paul Smith</h5>
                                                    <div class="text-warning mb-0 mt-1">
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                    </div>
                                                    <p class="font-13 text-muted">Very nice ! On the other hand, we denounce with righteous indignation and dislike men who are beguiled. </p>
                                                    <a class="like" href="javascript:;">
                                                        <span class="badge btn-danger-light rounded-pill py-2 px-3">
                                                                <i class="fe fe-heart me-1"></i>56</span>
                                                    </a>
                                                    <span class="reply">
                                                            <a href="javascript:;"><span class="badge btn-primary-light rounded-pill py-2 px-3"><i class="fe fe-corner-up-left me-1"></i>Reply</span></a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Add Review</div>
                                        </div>
                                        <div class="card-body">
                                            <form class="form-horizontal  m-t-20" action="{{url('index')}}">
                                                <div class="row">
                                                    <div class="col-xs-12 col-lg-6">
                                                        <div class="form-group">
                                                                <input class="form-control" type="text" required="" placeholder="Username*">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-lg-6">
                                                        <div class="form-group">
                                                                <input class="form-control" type="email" required="" placeholder="Email*">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-12">
                                                        <textarea class="form-control" rows="5">Your Review*</textarea>
                                                    </div>
                                                </div>
                                                <div class="">
                                                    <a href="javascript:void(0);" class="btn btn-primary btn-rounded  waves-effect waves-light">Submit</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <h3 class="p-3">Related Products</h3>
                                <div class="col-sm-6 col-md-6 col-xl-3 alert">
                                    <div class="card mb-0">
                                        <div class="product-grid5">
                                            <div class="product-image6 p-5">
                                                <a href="{{url('shop-description')}}" >
                                                    <img class="img-fluid br-7 w-100" src="{{asset('build/assets/images/pngs/1.png')}}" alt="img">
                                                </a>
                                            </div>
                                            <div class="card-body pt-0">
                                                <div class="product-content text-center">
                                                    <h1 class="title fw-semibold fs-20 my-0"><a href="{{url('shop-description')}}">Digital Camera</a></h1>
                                                    <div class="mb-2 text-warning">
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star-half-o text-warning"></i>
                                                        <i class="fa fa-star-o text-warning"></i>
                                                    </div>
                                                    <div class="price mb-2">$16,599<span class="ms-4 d-inline-flex">$19,799</span></div>
                                                    <span class="text-success fs-18 fw-semibold">In Stock</span>
                                                </div>
                                            </div>
                                            <div class="card-footer text-center">
                                                <a href="{{url('cart')}}" class="btn btn-primary mx-2 mb-1 w-sm"><i class="fe fe-shopping-cart me-2 d-inline-flex"></i>Add to cart</a>
                                                <a href="javascript:void(0);" class="btn btn-light mx-2 mb-1 w-sm"><i class="fe fe-share-2 me-2 text-dark d-inline-flex"></i>Share</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-xl-3 alert">
                                    <div class="card mb-0">
                                        <div class="product-grid5">
                                            <div class="product-image6 p-5">
                                                <a href="{{url('shop-description')}}" >
                                                    <img class="img-fluid br-7 w-100" src="{{asset('build/assets/images/pngs/2.png')}}" alt="img">
                                                </a>
                                            </div>
                                            <div class="card-body pt-0">
                                                <div class="product-content text-center">
                                                    <h1 class="title fw-semibold fs-20 my-0"><a href="{{url('shop-description')}}">Flower Pot</a></h1>
                                                    <div class="mb-2 text-warning">
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star-half-o text-warning"></i>
                                                        <i class="fa fa-star-o text-warning"></i>
                                                    </div>
                                                    <div class="price">$599<span class="ms-4 d-inline-flex">$799</span></div>
                                                    <span class="text-success fs-18 fw-semibold">In Stock</span>
                                                </div>
                                            </div>
                                            <div class="card-footer text-center">
                                                <a href="{{url('cart')}}" class="btn btn-primary mx-2 mb-1 w-sm"><i class="fe fe-shopping-cart me-2 d-inline-flex"></i>Add to cart</a>
                                                <a href="javascript:void(0);" class="btn btn-light mx-2 mb-1 w-sm"><i class="fe fe-share-2 me-2 text-dark d-inline-flex"></i>Share</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-xl-3 alert">
                                    <div class="card mb-0">
                                        <div class="product-grid5">
                                            <div class="product-image6 p-5">
                                                <a href="{{url('shop-description')}}" >
                                                    <img class="img-fluid br-7 w-100" src="{{asset('build/assets/images/pngs/3.png')}}" alt="img">
                                                </a>
                                            </div>
                                            <div class="card-body pt-0">
                                                <div class="product-content text-center">
                                                    <h1 class="title fw-semibold fs-20 my-0"><a href="{{url('shop-description')}}">Headphones</a></h1>
                                                    <div class="mb-2 text-warning">
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star-half-o text-warning"></i>
                                                        <i class="fa fa-star-o text-warning"></i>
                                                    </div>
                                                    <div class="price">$1,399<span class="ms-4 d-inline-flex">$1,599</span></div>
                                                    <span class="text-danger fs-18 fw-semibold">Out Of Stock</span>
                                                </div>
                                            </div>
                                            <div class="card-footer text-center">
                                                <a href="{{url('cart')}}" class="btn btn-primary mx-2 mb-1 w-sm"><i class="fe fe-shopping-cart me-2 d-inline-flex"></i>Add to cart</a>
                                                <a href="javascript:void(0);" class="btn btn-light mx-2 mb-1 w-sm"><i class="fe fe-share-2 me-2 text-dark d-inline-flex"></i>Share</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-xl-3 alert">
                                    <div class="card mb-0">
                                        <div class="product-grid5">
                                            <div class="product-image6 p-5">
                                                <a href="{{url('shop-description')}}" >
                                                    <img class="img-fluid br-7 w-100" src="{{asset('build/assets/images/pngs/4.png')}}" alt="img">
                                                </a>
                                            </div>
                                            <div class="card-body pt-0">
                                                <div class="product-content text-center">
                                                    <h1 class="title fw-semibold fs-20 my-0"><a href="{{url('shop-description')}}">Womens stylish bag</a></h1>
                                                    <div class="mb-2 text-warning">
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star-half-o text-warning"></i>
                                                        <i class="fa fa-star-o text-warning"></i>
                                                    </div>
                                                    <div class="price">$1,299<span class="ms-4 d-inline-flex">$1,899</span></div>
                                                    <span class="text-success fs-18 fw-semibold">In Stock</span>
                                                </div>
                                            </div>
                                            <div class="card-footer text-center">
                                                <a href="{{url('cart')}}" class="btn btn-primary mx-2 mb-1 w-sm"><i class="fe fe-shopping-cart me-2 d-inline-flex"></i>Add to cart</a>
                                                <a href="javascript:void(0);" class="btn btn-light mx-2 mb-1 w-sm"><i class="fe fe-share-2 me-2 text-dark d-inline-flex"></i>Share</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-1 CLOSED -->

@endsection

@section('scripts')

		<!-- SELECT2 JS -->
		<script src="{{asset('build/assets/plugins/select2/select2.full.min.js')}}"></script>
		@vite('resources/assets/js/select2.js')

@endsection
