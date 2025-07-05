
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Default Table</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Tables</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Default Table</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW-1 -->
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Basic Table</h3>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table card-table table-vcenter text-nowrap">
                                                <thead >
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Position</th>
                                                        <th>Salary</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">1</th>
                                                        <td>Joan Powell</td>
                                                        <td>Associate Developer</td>
                                                        <td>$450,870</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">2</th>
                                                        <td>Gavin Gibson</td>
                                                        <td>Account manager</td>
                                                        <td>$230,540</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">3</th>
                                                        <td>Julian Kerr</td>
                                                        <td>Senior Javascript Developer</td>
                                                        <td>$55,300</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">4</th>
                                                        <td>Cedric Kelly</td>
                                                        <td>Accountant</td>
                                                        <td>$234,100</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">5</th>
                                                        <td>Samantha May</td>
                                                        <td>Junior Technical Author</td>
                                                        <td>$43,198</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- table-responsive -->
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-1 CLOSED -->

                            <!-- ROW-2 -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-transparent border-0">
                                            <h3 class="card-title">Default Table</h3>
                                        </div>
                                        <div class="">
                                            <div class="grid-margin">
                                                <div class="">
                                                    <div class="table-responsive">
                                                        <table class="table card-table table-vcenter text-nowrap  align-items-center">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>Id</th>
                                                                    <th>Project Name</th>
                                                                    <th>Team</th>
                                                                    <th>Feedback</th>
                                                                    <th>Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>2345</td>
                                                                    <td class="text-sm font-weight-600">Megan Peters</td>
                                                                    <td><div class="avatar-group">
                                                                            <a class="avatar avatar-sm" data-bs-toggle="tooltip" href="javascript:void(0);" data-bs-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{asset('build/assets/images/users/19.jpg')}}"></a>
                                                                            <a class="avatar avatar-sm" data-bs-toggle="tooltip" href="javascript:void(0);" data-bs-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{asset('build/assets/images/users/20.jpg')}}"></a>
                                                                            <a class="avatar avatar-sm" data-bs-toggle="tooltip" href="javascript:void(0);" data-bs-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{asset('build/assets/images/users/1.jpg')}}"></a>
                                                                            <a class="avatar avatar-sm" data-bs-toggle="tooltip" href="javascript:void(0);" data-bs-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{asset('build/assets/images/users/2.jpg')}}"></a>
                                                                        </div>
                                                                    </td>
                                                                    <td>please check pricing Info </td>
                                                                    <td class="text-nowrap">July 13, 2018</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>4562</td>
                                                                    <td class="text-sm font-weight-600">Phil Vance</td>
                                                                    <td><div class="avatar-group">
                                                                            <a class="avatar avatar-sm" data-bs-toggle="tooltip" href="javascript:void(0);" data-bs-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{asset('build/assets/images/users/3.jpg')}}"></a>
                                                                            <a class="avatar avatar-sm" data-bs-toggle="tooltip" href="javascript:void(0);" data-bs-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{asset('build/assets/images/users/4.jpg')}}"></a>
                                                                            <a class="avatar avatar-sm" data-bs-toggle="tooltip" href="javascript:void(0);" data-bs-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{asset('build/assets/images/users/15.jpg')}}"></a>
                                                                            <a class="avatar avatar-sm" data-bs-toggle="tooltip" href="javascript:void(0);" data-bs-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{asset('build/assets/images/users/16.jpg')}}"></a>
                                                                        </div>
                                                                    </td>
                                                                    <td>New stock</td>
                                                                    <td class="text-nowrap">June 15, 2018</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>8765</td>
                                                                    <td class="text-sm font-weight-600">Adam Sharp</td>
                                                                    <td><div class="avatar-group">
                                                                            <a class="avatar avatar-sm" data-bs-toggle="tooltip" href="javascript:void(0);" data-bs-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{asset('build/assets/images/users/8.jpg')}}"></a>
                                                                            <a class="avatar avatar-sm" data-bs-toggle="tooltip" href="javascript:void(0);" data-bs-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{asset('build/assets/images/users/9.jpg')}}"></a>
                                                                            <a class="avatar avatar-sm" data-bs-toggle="tooltip" href="javascript:void(0);" data-bs-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{asset('build/assets/images/users/10.jpg')}}"></a>
                                                                            <a class="avatar avatar-sm" data-bs-toggle="tooltip" href="javascript:void(0);" data-bs-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{asset('build/assets/images/users/11.jpg')}}"></a>
                                                                        </div>
                                                                    </td>
                                                                    <td>Daily updates</td>
                                                                    <td class="text-nowrap">July 8, 2018</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>2665</td>
                                                                    <td class="text-sm font-weight-600">Samantha Slater</td>
                                                                    <td><div class="avatar-group">
                                                                            <a class="avatar avatar-sm" data-bs-toggle="tooltip" href="javascript:void(0);" data-bs-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{asset('build/assets/images/users/12.jpg')}}"></a>
                                                                            <a class="avatar avatar-sm" data-bs-toggle="tooltip" href="javascript:void(0);" data-bs-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{asset('build/assets/images/users/2.jpg')}}"></a>
                                                                            <a class="avatar avatar-sm" data-bs-toggle="tooltip" href="javascript:void(0);" data-bs-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{asset('build/assets/images/users/18.jpg')}}"></a>
                                                                            <a class="avatar avatar-sm" data-bs-toggle="tooltip" href="javascript:void(0);" data-bs-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{asset('build/assets/images/users/1.jpg')}}"></a>
                                                                        </div>
                                                                    </td>
                                                                    <td>available item list</td>
                                                                    <td class="text-nowrap">June 28, 2018</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>1245</td>
                                                                    <td class="text-sm font-weight-600">Joanne Nash</td>
                                                                    <td><div class="avatar-group">
                                                                            <a class="avatar avatar-sm" data-bs-toggle="tooltip" href="javascript:void(0);" data-bs-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{asset('build/assets/images/users/15.jpg')}}"></a>
                                                                            <a class="avatar avatar-sm" data-bs-toggle="tooltip" href="javascript:void(0);" data-bs-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{asset('build/assets/images/users/16.jpg')}}"></a>
                                                                            <a class="avatar avatar-sm" data-bs-toggle="tooltip" href="javascript:void(0);" data-bs-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{asset('build/assets/images/users/17.jpg')}}"></a>
                                                                            <a class="avatar avatar-sm" data-bs-toggle="tooltip" href="javascript:void(0);" data-bs-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{asset('build/assets/images/users/18.jpg')}}"></a>
                                                                        </div>
                                                                    </td>
                                                                    <td>Provide Best Services</td>
                                                                    <td class="text-nowrap">July 2, 2018</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-2 CLOSED -->

                            <!-- ROW-3 OPEN -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Primary Table</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered card-table table-vcenter text-nowrap table-primary border-top-0 border-0">
                                                    <thead  class="bg-primary text-white">
                                                        <tr>
                                                            <th class="text-white">ID</th>
                                                            <th class="text-white">Name</th>
                                                            <th class="text-white">Position</th>
                                                            <th class="text-white">Salary</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">1</th>
                                                            <td>Joan Powell</td>
                                                            <td>Associate Developer</td>
                                                            <td>$450,870</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">2</th>
                                                            <td>Gavin Gibson</td>
                                                            <td>Account manager</td>
                                                            <td>$230,540</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">3</th>
                                                            <td>Julian Kerr</td>
                                                            <td>Senior Javascript Developer</td>
                                                            <td>$55,300</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">4</th>
                                                            <td>Cedric Kelly</td>
                                                            <td>Accountant</td>
                                                            <td>$234,100</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">5</th>
                                                            <td>Samantha May</td>
                                                            <td>Junior Technical Author</td>
                                                            <td>$43,198</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-3 CLOSED -->

                            <!-- ROW-4 OPEN -->
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Secondary Table</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered card-table table-vcenter text-nowrap table-secondary border-top-0 border-0">
                                                    <thead  class="bg-secondary text-white">
                                                        <tr>
                                                            <th class="text-white">ID</th>
                                                            <th class="text-white">Name</th>
                                                            <th class="text-white">Position</th>
                                                            <th class="text-white">Salary</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">1</th>
                                                            <td>Joan Powell</td>
                                                            <td>Associate Developer</td>
                                                            <td>$450,870</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">2</th>
                                                            <td>Gavin Gibson</td>
                                                            <td>Account manager</td>
                                                            <td>$230,540</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">3</th>
                                                            <td>Julian Kerr</td>
                                                            <td>Senior Javascript Developer</td>
                                                            <td>$55,300</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">4</th>
                                                            <td>Cedric Kelly</td>
                                                            <td>Accountant</td>
                                                            <td>$234,100</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">5</th>
                                                            <td>Samantha May</td>
                                                            <td>Junior Technical Author</td>
                                                            <td>$43,198</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- table-responsive -->
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-4 CLOSED -->

                            <!-- ROW-5 OPEN -->
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Success Table</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered card-table table-vcenter text-nowrap table-success border-top-0 border-0">
                                                    <thead  class="bg-success text-white">
                                                        <tr>
                                                            <th class="text-white">ID</th>
                                                            <th class="text-white">Name</th>
                                                            <th class="text-white">Position</th>
                                                            <th class="text-white">Salary</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">1</th>
                                                            <td>Joan Powell</td>
                                                            <td>Associate Developer</td>
                                                            <td>$450,870</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">2</th>
                                                            <td>Gavin Gibson</td>
                                                            <td>Account manager</td>
                                                            <td>$230,540</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">3</th>
                                                            <td>Julian Kerr</td>
                                                            <td>Senior Javascript Developer</td>
                                                            <td>$55,300</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">4</th>
                                                            <td>Cedric Kelly</td>
                                                            <td>Accountant</td>
                                                            <td>$234,100</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">5</th>
                                                            <td>Samantha May</td>
                                                            <td>Junior Technical Author</td>
                                                            <td>$43,198</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- table-responsive -->
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-5 CLOSED -->

                            <!-- ROW-6 OPEN -->
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Warning Table</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered card-table table-vcenter text-nowrap table-warning border-top-0 border-0">
                                                    <thead  class="bg-warning text-white">
                                                        <tr>
                                                            <th class="text-white">ID</th>
                                                            <th class="text-white">Name</th>
                                                            <th class="text-white">Position</th>
                                                            <th class="text-white">Salary</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">1</th>
                                                            <td>Joan Powell</td>
                                                            <td>Associate Developer</td>
                                                            <td>$450,870</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">2</th>
                                                            <td>Gavin Gibson</td>
                                                            <td>Account manager</td>
                                                            <td>$230,540</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">3</th>
                                                            <td>Julian Kerr</td>
                                                            <td>Senior Javascript Developer</td>
                                                            <td>$55,300</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">4</th>
                                                            <td>Cedric Kelly</td>
                                                            <td>Accountant</td>
                                                            <td>$234,100</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">5</th>
                                                            <td>Samantha May</td>
                                                            <td>Junior Technical Author</td>
                                                            <td>$43,198</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- table-responsive -->
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-6 CLOSED -->

                            <!-- ROW-7 OPEN -->
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Danger Table</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered card-table table-vcenter text-nowrap table-danger border-top-0 border-0">
                                                    <thead  class="bg-danger text-white">
                                                        <tr>
                                                            <th class="text-white">ID</th>
                                                            <th class="text-white">Name</th>
                                                            <th class="text-white">Position</th>
                                                            <th class="text-white">Salary</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">1</th>
                                                            <td>Joan Powell</td>
                                                            <td>Associate Developer</td>
                                                            <td>$450,870</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">2</th>
                                                            <td>Gavin Gibson</td>
                                                            <td>Account manager</td>
                                                            <td>$230,540</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">3</th>
                                                            <td>Julian Kerr</td>
                                                            <td>Senior Javascript Developer</td>
                                                            <td>$55,300</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">4</th>
                                                            <td>Cedric Kelly</td>
                                                            <td>Accountant</td>
                                                            <td>$234,100</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">5</th>
                                                            <td>Samantha May</td>
                                                            <td>Junior Technical Author</td>
                                                            <td>$43,198</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- table-responsive -->
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-7 CLOSED -->
                            
                            <!-- ROW-8 OPEN -->
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Info Table</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered card-table table-vcenter text-nowrap table-info border-top-0 border-0">
                                                    <thead  class="bg-info text-white">
                                                        <tr>
                                                            <th class="text-white">ID</th>
                                                            <th class="text-white">Name</th>
                                                            <th class="text-white">Position</th>
                                                            <th class="text-white">Salary</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">1</th>
                                                            <td>Joan Powell</td>
                                                            <td>Associate Developer</td>
                                                            <td>$450,870</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">2</th>
                                                            <td>Gavin Gibson</td>
                                                            <td>Account manager</td>
                                                            <td>$230,540</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">3</th>
                                                            <td>Julian Kerr</td>
                                                            <td>Senior Javascript Developer</td>
                                                            <td>$55,300</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">4</th>
                                                            <td>Cedric Kelly</td>
                                                            <td>Accountant</td>
                                                            <td>$234,100</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">5</th>
                                                            <td>Samantha May</td>
                                                            <td>Junior Technical Author</td>
                                                            <td>$43,198</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- table-responsive -->
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-8 CLOSED -->

@endsection

@section('scripts')



@endsection
