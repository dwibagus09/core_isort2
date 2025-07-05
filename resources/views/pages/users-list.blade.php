
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">User List</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Advanced Ui</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">User List</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- row start -->
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header d-flex">
                                            <h6 class="card-title">Sample Data Table</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive ">
                                                <table id="example-2" class="table table-striped table-bordered text-nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th class="wd-2">
                                                                Photo
                                                            </th>
                                                            <th>Name</th>
                                                            <th>Role</th>
                                                            <th>Last Active</th>
                                                            <th>Country</th>
                                                            <th>Verification</th>
                                                            <th>Joined Date</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="">
                                                                <div class="avatar avatar-sm rounded-circle mt-2">
                                                                    <img alt="avatar " class="rounded-circle" src="{{asset('build/assets/images/users/9.jpg')}}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="fs-14 fw-semibold text-dark mb-0">Henry</p>
                                                                <p class="fs-13 text-muted mb-0">Henry.c6633@gmail.com</p>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">User</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge bg-transparent text-muted fs-13">4 days ago</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">Japan</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge fw-semibold badge-pill bg-success-transparent text-success fs-11">Verified</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">20-04-2020</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <a href="javascript:void(0);" class="btn btn-sm btn-primary-light me-2" data-bs-toggle="tooltip" title="Edit">
                                                                    <i class="zmdi zmdi-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="">
                                                                <div class="avatar avatar-sm rounded-circle mt-2">
                                                                    <img alt="avatar " class="rounded-circle" src="{{asset('build/assets/images/users/2.jpg')}}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="fs-14 fw-semibold text-dark mb-0">Smith</p>
                                                                <p class="fs-13 text-muted mb-0">smitha2524@gmail.com</p>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">Admin</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge bg-transparent text-muted fs-13">6 days ago</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">Italy</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge fw-semibold badge-pill bg-secondary-transparent text-secondary fs-11">Not Verified</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">10-12-2022</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <a href="javascript:void(0);" class="btn btn-sm btn-primary-light me-2" data-bs-toggle="tooltip" title="Edit">
                                                                    <i class="zmdi zmdi-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="">
                                                                <div class="avatar avatar bg-secondary text-white rounded-circle">
                                                                    D
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="fs-14 fw-semibold text-dark mb-0">Jony James</p>
                                                                <p class="fs-13 text-muted mb-0">Jony.j@gmail.com</p>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">User</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge bg-transparent text-muted fs-13">2 days ago</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">Chicago</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge fw-semibold badge-pill bg-success-transparent text-success fs-11">Verified</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">1-9-2021</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <a href="javascript:void(0);" class="btn btn-sm btn-primary-light me-2" data-bs-toggle="tooltip" title="Edit">
                                                                    <i class="zmdi zmdi-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="">
                                                                <div class="avatar avatar-sm rounded-circle mt-2">
                                                                    <img alt="avatar " class="rounded-circle" src="{{asset('build/assets/images/users/10.jpg')}}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="fs-14 fw-semibold text-dark mb-0">Dani</p>
                                                                <p class="fs-13 text-muted mb-0">Dani1568@gmail.in</p>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">Agent</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge bg-transparent text-muted fs-13">1 day ago</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">Austria</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge fw-semibold badge-pill bg-secondary-transparent text-secondary fs-11">Not Verified</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">30-3-2021</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <a href="javascript:void(0);" class="btn btn-sm btn-primary-light me-2" data-bs-toggle="tooltip" title="Edit">
                                                                    <i class="zmdi zmdi-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="">
                                                                <div class="avatar avatar-sm rounded-circle mt-2">
                                                                    <img alt="avatar " class="rounded-circle" src="{{asset('build/assets/images/users/3.jpg')}}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="fs-14 fw-semibold text-dark mb-0">Mary</p>
                                                                <p class="fs-13 text-muted mb-0">mary009@gmail.com</p>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">Admin</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge bg-transparent text-muted fs-13">2 hr ago</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">Paris</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge fw-semibold badge-pill bg-success-transparent text-success fs-11">Verified</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">21-4-2021</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <a href="javascript:void(0);" class="btn btn-sm btn-primary-light me-2" data-bs-toggle="tooltip" title="Edit">
                                                                    <i class="zmdi zmdi-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="">
                                                                <div class="avatar avatar-sm rounded-circle mt-2">
                                                                    <img alt="avatar " class="rounded-circle" src="{{asset('build/assets/images/users/11.jpg')}}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="fs-14 fw-semibold text-dark mb-0">Charles</p>
                                                                <p class="fs-13 text-muted mb-0">charles.charlie@gmail.com</p>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">User</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge bg-transparent text-muted fs-13">22 hrs ago</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">Los Vegas</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge fw-semibold badge-pill bg-success-transparent text-success fs-11">Verified</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">09-11-2021</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <a href="javascript:void(0);" class="btn btn-sm btn-primary-light me-2" data-bs-toggle="tooltip" title="Edit">
                                                                    <i class="zmdi zmdi-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="">
                                                                <div class="avatar avatar-sm rounded-circle mt-2">
                                                                    <img alt="avatar " class="rounded-circle" src="{{asset('build/assets/images/users/12.jpg')}}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="fs-14 fw-semibold text-dark mb-0">Richard</p>
                                                                <p class="fs-13 text-muted mb-0">richard1885@gmail.com</p>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">Manager</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge bg-transparent text-muted fs-13">1 hr ago</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">Japan</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge fw-semibold badge-pill bg-secondary-transparent text-secondary fs-11">Not Verified</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">13-04-2021</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <a href="javascript:void(0);" class="btn btn-sm btn-primary-light me-2" data-bs-toggle="tooltip" title="Edit">
                                                                    <i class="zmdi zmdi-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="">
                                                                <div class="avatar avatar-sm rounded-circle mt-2">
                                                                    <img alt="avatar " class="rounded-circle" src="{{asset('build/assets/images/users/6.jpg')}}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="fs-14 fw-semibold text-dark mb-0">Linda</p>
                                                                <p class="fs-12 text-muted mb-0">linda.123@gmail.com</p>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">Admin</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge bg-transparent text-muted fs-13">10 min ago</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">Russia</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge fw-semibold badge-pill bg-success-transparent text-success fs-11">Verified</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">09-07-2021</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <a href="javascript:void(0);" class="btn btn-sm btn-primary-light me-2" data-bs-toggle="tooltip" title="Edit">
                                                                    <i class="zmdi zmdi-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="">
                                                                <div class="avatar avatar bg-warning text-white rounded-circle">
                                                                    A
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="fs-14 fw-semibold text-dark mb-0">Emily</p>
                                                                <p class="fs-13 text-muted mb-0">emily.j@hotmail.in</p>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">User</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge bg-transparent text-muted fs-13">1 day ago</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">France</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge fw-semibold badge-pill bg-success-transparent text-success fs-11">Verified</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">16-11-2021</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <a href="javascript:void(0);" class="btn btn-sm btn-primary-light me-2" data-bs-toggle="tooltip" title="Edit">
                                                                    <i class="zmdi zmdi-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="">
                                                                <div class="avatar avatar-sm rounded-circle mt-2">
                                                                    <img alt="avatar " class="rounded-circle" src="{{asset('build/assets/images/users/14.jpg')}}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="fs-14 fw-semibold text-dark mb-0">Tony</p>
                                                                <p class="fs-13 text-muted mb-0">tonay568@gmail.org</p>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">Agent</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge bg-transparent text-muted fs-13">10 min ago</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">Canada</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge fw-semibold badge-pill bg-success-transparent text-success fs-11">Verified</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">13-12-2021</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <a href="javascript:void(0);" class="btn btn-sm btn-primary-light me-2" data-bs-toggle="tooltip" title="Edit">
                                                                    <i class="zmdi zmdi-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="">
                                                                <div class="avatar avatar-sm rounded-circle mt-2">
                                                                    <img alt="avatar " class="rounded-circle" src="{{asset('build/assets/images/users/13.jpg')}}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="fs-14 fw-semibold text-dark mb-0">Stanley</p>
                                                                <p class="fs-13 text-muted mb-0">Stanley.l@hotmail.com</p>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">Admin</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge bg-transparent text-muted fs-13">5 min ago</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">Spain</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge fw-semibold badge-pill bg-secondary-transparent text-secondary fs-11">Not Verified</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">07-06-2021</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <a href="javascript:void(0);" class="btn btn-sm btn-primary-light me-2" data-bs-toggle="tooltip" title="Edit">
                                                                    <i class="zmdi zmdi-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="">
                                                                <div class="avatar avatar-sm rounded-circle mt-2">
                                                                    <img alt="avatar " class="rounded-circle" src="{{asset('build/assets/images/users/5.jpg')}}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="fs-14 fw-semibold text-dark mb-0">Sylvia</p>
                                                                <p class="fs-13 text-muted mb-0">sylvia.N@gmail.com</p>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">User</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge bg-transparent text-muted fs-13">2 days ago</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">Italy</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge fw-semibold badge-pill bg-success-transparent text-success fs-11">Verified</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">31-12-2021</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <a href="javascript:void(0);" class="btn btn-sm btn-primary-light me-2" data-bs-toggle="tooltip" title="Edit">
                                                                    <i class="zmdi zmdi-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="">
                                                                <div class="avatar avatar-sm rounded-circle mt-2">
                                                                    <img alt="avatar " class="rounded-circle" src="{{asset('build/assets/images/users/15.jpg')}}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="fs-14 fw-semibold text-dark mb-0">Melvin</p>
                                                                <p class="fs-13 text-muted mb-0">melvin@gmail.com</p>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">Admin</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge bg-transparent text-muted fs-13">1 hr ago</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">Mexico</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge fw-semibold badge-pill bg-success-transparent text-success fs-11">Verified</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">14-09-2021</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <a href="javascript:void(0);" class="btn btn-sm btn-primary-light me-2" data-bs-toggle="tooltip" title="Edit">
                                                                    <i class="zmdi zmdi-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="">
                                                                <div class="avatar avatar-sm rounded-circle mt-2">
                                                                    <img alt="avatar " class="rounded-circle" src="{{asset('build/assets/images/users/7.jpg')}}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="fs-14 fw-semibold text-dark mb-0">Josephine</p>
                                                                <p class="fs-13 text-muted mb-0">josephine@hotmail.in</p>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">User</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge bg-transparent text-muted fs-13">1 day ago</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">USA</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="badge fw-semibold badge-pill bg-success-transparent text-success fs-11">Verified</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <span class="text-muted fs-13">06-08-2021</span>
                                                            </td>
                                                            <td class="align-middle">
                                                                <a href="javascript:void(0);" class="btn btn-sm btn-primary-light me-2" data-bs-toggle="tooltip" title="Edit">
                                                                    <i class="zmdi zmdi-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- table-wrapper -->
                                    </div>
                                    <!-- section-wrapper -->
                                </div>
                            </div>
                            <!-- row closed -->

@endsection

@section('scripts')

		<!-- SELECT2 JS -->
		<script src="{{asset('build/assets/plugins/select2/select2.full.min.js')}}"></script>
		@vite('resources/assets/js/select2.js')

@endsection
