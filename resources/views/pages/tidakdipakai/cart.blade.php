
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Cart</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">E-Commerce</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Cart</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW-1 OPEN -->
                                <div class="row">
                                    <div class="col-xl-8 col-md-12">
                                        <div class="card cart">
                                            <div class="card-header">
                                                <h3 class="card-title">Shopping Cart</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-vcenter text-nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th>Product</th>
                                                                <th>Price</th>
                                                                <th class="w-25">Quantity</th>
                                                                <th>Subtotal</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-wrap">
                                                                    <img src="{{asset('build/assets/images/pngs/1.png')}}" alt="" class="h-8 br-5"> <span class="ms-2">Digital Camera</span>
                                                                </td>
                                                                <td class="fw-semibold">$568</td>
                                                                <td>
                                                                    <div class="handle-counter" id="handleCounter1">
                                                                        <button type="button" class="counter-minus btn btn-white lh-2 shadow-none border" >
                                                                            <i class="fa fa-minus text-muted align-middle"></i>
                                                                        </button>
                                                                        <input type="text" value="2" class="qty">
                                                                        <button type="button" class="counter-plus btn btn-white lh-2 shadow-none border">
                                                                            <i class="fa fa-plus text-muted align-middle"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                                <td class="font-weight-bold">$1,589</td>
                                                                <td>
                                                                    <a href="javascript:void(0);" class="btn btn-primary-light border-0 me-3 " data-bs-toggle="tooltip" data-bs-placement="top" title="Save for Washlist"><i class="icon icon-heart align-middle"></i></a>
                                                                    <a href="javascript:void(0);" class="btn btn-secondary-light border-0 me-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove"><i class="icon icon-trash align-middle"></i></a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-wrap">
                                                                    <img src="{{asset('build/assets/images/pngs/4.png')}}" alt="" class="h-8 br-5"><span class="ms-2">Stylish BackPack</span>
                                                                </td>
                                                                <td class="fw-semibold">$1,027</td>
                                                                <td>
                                                                    <div class="handle-counter" id="handleCounter2">
                                                                        <button type="button" class="counter-minus btn btn-white lh-2 shadow-none border" >
                                                                            <i class="fa fa-minus text-muted align-middle"></i>
                                                                        </button>
                                                                        <input type="text" value="2" class="qty">
                                                                        <button type="button" class="counter-plus btn btn-white lh-2 shadow-none border">
                                                                            <i class="fa fa-plus text-muted align-middle"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                                <td class="font-weight-bold">$3,500</td>
                                                                <td>
                                                                    <a href="javascript:void(0);" class="btn btn-primary-light border-0  me-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Save for Washlist"><i class="icon icon-heart align-middle"></i></a>
                                                                    <a href="javascript:void(0);" class="btn btn-secondary-light border-0  me-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove"><i class="icon icon-trash align-middle"></i></a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-wrap">
                                                                    <img src="{{asset('build/assets/images/pngs/8.png')}}" alt="" class="h-8 br-5"><span class="ms-2">Sports shoe</span>
                                                                </td>
                                                                <td class="fw-semibold">$1,589</td>
                                                                <td>
                                                                    <div class="handle-counter" id="handleCounter3">
                                                                        <button type="button" class="counter-minus btn btn-white lh-2 shadow-none border" >
                                                                            <i class="fa fa-minus text-muted align-middle"></i>
                                                                        </button>
                                                                        <input type="text" value="2" class="qty">
                                                                        <button type="button" class="counter-plus btn btn-white lh-2 shadow-none border">
                                                                            <i class="fa fa-plus text-muted align-middle"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                                <td class="font-weight-bold">$4,000</td>
                                                                <td>
                                                                    <a href="javascript:void(0);" class="btn btn-primary-light border-0  me-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Save for Washlist"><i class="icon icon-heart align-middle"></i></a>
                                                                    <a href="javascript:void(0);" class="btn btn-secondary-light border-0  me-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove"><i class="icon icon-trash align-middle"></i></a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-wrap">
                                                                    <img src="{{asset('build/assets/images/pngs/3.png')}}" alt="" class="h-8 br-5"><span class="ms-2">Wireless headphones</span>
                                                                </td>
                                                                <td class="fw-semibold">$1,500</td>
                                                                <td>
                                                                    <div class="handle-counter" id="handleCounter4">
                                                                        <button type="button" class="counter-minus btn btn-white lh-2 shadow-none border" >
                                                                            <i class="fa fa-minus text-muted align-middle"></i>
                                                                        </button>
                                                                        <input type="text" value="2" class="qty">
                                                                        <button type="button" class="counter-plus btn btn-white lh-2 shadow-none border">
                                                                            <i class="fa fa-plus text-muted align-middle"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                                <td class="font-weight-bold">$1,409</td>
                                                                <td>
                                                                    <a href="javascript:void(0);" class="btn btn-primary-light border-0  me-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Save for Washlist"><i class="icon icon-heart align-middle"></i></a>
                                                                    <a href="javascript:void(0);" class="btn btn-secondary-light border-0  me-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove"><i class="icon icon-trash align-middle"></i></a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div><br>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="input-group border-bottom-dashed pb-5">
                                                    <input type="text" class="form-control" placeholder="Promo Code">
                                                    <button class="btn btn-primary"> Apply</button>
                                                </div>
                                                <br>
                                                <div class="mt-3">
                                                    <ul class="list-group">
                                                        <li class="list-group-item">
                                                            Sub Total
                                                            <span class="badgetext font-weight-bold mb-0">$4,360</span>
                                                        </li>
                                                        <li class="list-group-item text-muted">
                                                            Discount
                                                            <span class="badgetext font-weight-bold mb-0">5%</span>
                                                        </li>
                                                        <li class="list-group-item text-muted">
                                                            Shipping
                                                            <span class="badgetext font-weight-bold mb-0">Free</span>
                                                        </li>
                                                        <li class="list-group-item text-muted">
                                                            Tax
                                                            <span class="badgetext font-weight-bold mb-0">+$10.00</span>
                                                        </li>
                                                        <li class="list-group-item fw-bold">
                                                            Total
                                                            <span class="badgetext  fw-bold mb-0">$3,976</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="text-end mt-4">
                                                    <a href="{{url('shop')}}" class="btn btn-primary m-2"><i class="fa fa-arrow-left me-2"></i>Continue Shopping</a>
                                                    <a href="{{url('checkout')}}" class="btn btn-success m-2">Check out<i class="fa fa-arrow-right ms-2"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- COL-END -->

@endsection

@section('scripts')

		<!-- Handle Counter js -->
		@vite('resources/assets/js/handlecounter.js')

@endsection
