
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Email Inbox</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Pages</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Email Inbox</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW-1 OPEN -->
                            <div class="row mail-inbox">
                                <div class="col-md-12 col-lg-3">
                                    <div class="card p-3">
                                        <div class="list-group list-group-transparent mb-0 mail-inbox">
                                            <div class="mb-4 text-center">
                                                <div class="">
                                                    <a href="{{url('emailservices')}}" class="btn btn-secondary btn-lg btn-block">Compose</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="list-group-item list-group-item-action d-flex align-items-center active">
                                                <span class="icon me-3"><i class="fe fe-inbox"></i></span>Inbox <span class="ms-auto badge bg-success">14</span>
                                            </a>
                                            <a href="javascript:void(0);" class="list-group-item list-group-item-action d-flex align-items-center">
                                                <span class="icon me-3"><i class="fe fe-send"></i></span>Sent Mail
                                            </a>
                                            <a href="javascript:void(0);" class="list-group-item list-group-item-action d-flex align-items-center">
                                                <span class="icon me-3"><i class="fe fe-alert-circle"></i></span>Important <span class="ms-auto badge bg-danger">3</span>
                                            </a>
                                            <a href="javascript:void(0);" class="list-group-item list-group-item-action d-flex align-items-center">
                                                <span class="icon me-3"><i class="fe fe-star"></i></span>Starred
                                            </a>
                                            <a href="javascript:void(0);" class="list-group-item list-group-item-action d-flex align-items-center">
                                                <span class="icon me-3"><i class="fe fe-file"></i></span>Drafts
                                            </a>
                                            <a href="javascript:void(0);" class="list-group-item list-group-item-action d-flex align-items-center">
                                                <span class="icon me-3"><i class="fe fe-tag"></i></span>Tags
                                            </a>
                                            <a href="javascript:void(0);" class="list-group-item list-group-item-action d-flex align-items-center">
                                                <span class="icon me-3"><i class="fe fe-trash-2"></i></span>Trash
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title ">Labels</h5>
                                        </div>
                                        <div class="list-group list-group-transparent">
                                            <a href="javascript:void(0);" class="list-group-item list-group-item-action d-flex align-items-center">
                                                <span class="icon me-3"><i class="fa fa-briefcase"></i></span>Works
                                            </a>
                                            <a href="javascript:void(0);" class="list-group-item list-group-item-action d-flex align-items-center">
                                                <span class="icon me-3"><i class="fa fa-folder-open"></i></span>Job
                                            </a>
                                            <a href="javascript:void(0);" class="list-group-item list-group-item-action d-flex align-items-center">
                                                <span class="icon me-3"><i class="fa fa-user"></i></span>Client
                                            </a>
                                            <a href="javascript:void(0);" class="list-group-item list-group-item-action d-flex align-items-center">
                                                <span class="icon me-3"><i class="fa fa-newspaper-o"></i></span>News
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="online-status d-flex justify-content-between align-items-center mt-4 mb-2 ms-2">
                                            <h5 class="card-title chat ms-3">Chats</h5>
                                            <div class="status offline online"> <h6 class="online text-end">online</h6></div>
                                        </div>
                                        <ul class="mail-chats p-0">
                                            <li class="chat-persons p-3">
                                                <a href="javascript:void(0);">
                                                    <span class="pro-pic"><img src="{{asset('build/assets/images/users/4.jpg')}}" alt=""></span>
                                                    <div class="user">
                                                        <p class="u-name">David</p>
                                                        <p class="u-designation">Python Developer</p>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- LIST PERSON -->
                                            <li class="chat-persons p-3 border-top">
                                                <a href="javascript:void(0);">
                                                    <span class="pro-pic"><img src="{{asset('build/assets/images/users/1.jpg')}}" alt=""></span>
                                                    <div class="user">
                                                        <p class="u-name">Stella Johnson</p>
                                                        <p class="u-designation">SEO Expert</p>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- LIST PERSON -->
                                            <li class="chat-persons p-3 border-top">
                                                <a href="javascript:void(0);">
                                                    <span class="pro-pic"><img src="{{asset('build/assets/images/users/15.jpg')}}" alt=""></span>
                                                    <div class="user">
                                                        <p class="u-name">Marina Michel</p>
                                                        <p class="u-designation">Business Development</p>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- LIST PERSON -->
                                            <li class="chat-persons p-3 border-top">
                                                <a href="javascript:void(0);">
                                                    <span class="pro-pic"><img src="{{asset('build/assets/images/users/2.jpg')}}" alt=""></span>
                                                    <div class="user">
                                                        <p class="u-name">Edward Fletcher</p>
                                                        <p class="u-designation">UI/UX Designer</p>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- LIST PERSON -->
                                        </ul>
                                        <!-- CHAT -->
                                    </div>
                                </div><!-- COL-END -->
                                <div class="col-md-12 col-lg-9">
                                    <div class="card">
                                        <div class="card-body p-6">
                                            <div class="inbox-body">
                                                <div class="mail-option">
                                                    <div class="chk-all pe-0">
                                                        <div class="btn-group">
                                                            <a data-bs-toggle="dropdown" href="javascript:void(0);" class="btn mini all border-0" aria-expanded="false">
                                                                All
                                                                <i class="fa fa-angle-down ms-1"></i>
                                                            </a>
                                                            <ul class="dropdown-menu">
                                                                <li><a href="javascript:void(0);"> None</a></li>
                                                                <li><a href="javascript:void(0);"> Read</a></li>
                                                                <li><a href="javascript:void(0);"> Unread</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="btn-group">
                                                        <a data-bs-original-title="Refresh" href="javascript:void(0);" class="btn mini tooltips py-1">
                                                            <i class=" fa fa-refresh"></i>
                                                        </a>
                                                    </div>
                                                    <div class="btn-group hidden-phone">
                                                        <a data-bs-toggle="dropdown" href="javascript:void(0);" class="btn mini blue py-1" aria-expanded="false">
                                                            More
                                                            <i class="fa fa-angle-down "></i>
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="javascript:void(0);"><i class="fa fa-pencil me-2 d-inline-flex"></i> Mark as Read</a></li>
                                                            <li><a href="javascript:void(0);"><i class="fa fa-ban me-2 d-inline-flex"></i> Spam</a></li>
                                                            <li class="divider"></li>
                                                            <li><a href="javascript:void(0);"><i class="fa fa-trash-o me-2 d-inline-flex"></i> Delete</a></li>
                                                        </ul>
                                                    </div>
                                                    <ul class="unstyled inbox-pagination">
                                                        <li><span>1-50 of 234</span></li>
                                                        <li>
                                                            <a class="np-btn" href="javascript:void(0);"><i class="fa fa-angle-right pagination-right"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-inbox table-hover text-nowrap">
                                                        <tbody>
                                                            <tr class="unread">
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star"></i></td>
                                                                <td class="view-message  dont-show">PHPClass</td>
                                                                <td class="view-message ">Added a new class: Login Class Fast Site</td>
                                                                <td class="view-message  inbox-small-cells"><i class="fa fa-paperclip"></i></td>
                                                                <td class="view-message  text-end">9:27 AM</td>
                                                            </tr>
                                                            <tr class="unread">
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star"></i></td>
                                                                <td class="view-message dont-show">Google Webmaster </td>
                                                                <td class="view-message">Improve the search presence of WebSite</td>
                                                                <td class="view-message inbox-small-cells"></td>
                                                                <td class="view-message text-end">March 15</td>
                                                            </tr>
                                                            <tr >
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star"></i></td>
                                                                <td class="view-message dont-show">JW Player</td>
                                                                <td class="view-message">Last Chance: Upgrade to Pro for </td>
                                                                <td class="view-message inbox-small-cells"></td>
                                                                <td class="view-message text-end">March 15</td>
                                                            </tr>
                                                            <tr >
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star"></i></td>
                                                                <td class="view-message dont-show">Tim Reid, S P N</td>
                                                                <td class="view-message">Boost Your Website Traffic</td>
                                                                <td class="view-message inbox-small-cells"></td>
                                                                <td class="view-message text-end">April 01</td>
                                                            </tr>
                                                            <tr >
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star inbox-started"></i></td>
                                                                <td class="view-message dont-show">Freelancer.com <span class="label label-danger float-end br-5">urgent</span></td>
                                                                <td class="view-message">Stop wasting your visitors </td>
                                                                <td class="view-message inbox-small-cells"></td>
                                                                <td class="view-message text-end">May 23</td>
                                                            </tr>
                                                            <tr >
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star inbox-started"></i></td>
                                                                <td class="view-message dont-show">WOW Slider </td>
                                                                <td class="view-message">New WOW Slider v7.8 - 67% off</td>
                                                                <td class="view-message inbox-small-cells"><i class="fa fa-paperclip"></i></td>
                                                                <td class="view-message text-end">March 14</td>
                                                            </tr>
                                                            <tr >
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star inbox-started"></i></td>
                                                                <td class="view-message dont-show">LinkedIn Pulse</td>
                                                                <td class="view-message">The One Sign Your Co-Worker Will Stab</td>
                                                                <td class="view-message inbox-small-cells"><i class="fa fa-paperclip"></i></td>
                                                                <td class="view-message text-end">Feb 19</td>
                                                            </tr>
                                                            <tr >
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star"></i></td>
                                                                <td class="view-message dont-show">Drupal Community<span class="label label-success float-end br-5">megazine</span></td>
                                                                <td class="view-message view-message">Welcome to the Drupal Community</td>
                                                                <td class="view-message inbox-small-cells"></td>
                                                                <td class="view-message text-end">March 04</td>
                                                            </tr>
                                                            <tr >
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star"></i></td>
                                                                <td class="view-message dont-show">Facebook</td>
                                                                <td class="view-message view-message">Somebody requested a new password </td>
                                                                <td class="view-message inbox-small-cells"></td>
                                                                <td class="view-message text-end">June 13</td>
                                                            </tr>
                                                            <tr >
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star"></i></td>
                                                                <td class="view-message dont-show">Skype <span class="label label-info float-end br-5">family</span></td>
                                                                <td class="view-message view-message">Password successfully changed</td>
                                                                <td class="view-message inbox-small-cells"></td>
                                                                <td class="view-message text-end">March 24</td>
                                                            </tr>
                                                            <tr >
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star inbox-started"></i></td>
                                                                <td class="view-message dont-show">Google+</td>
                                                                <td class="view-message">alireza, do you know</td>
                                                                <td class="view-message inbox-small-cells"></td>
                                                                <td class="view-message text-end">March 09</td>
                                                            </tr>
                                                            <tr >
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star inbox-started"></i></td>
                                                                <td class="dont-show">Zoosk </td>
                                                                <td class="view-message">7 new singles we think you'll like</td>
                                                                <td class="view-message inbox-small-cells"><i class="fa fa-paperclip"></i></td>
                                                                <td class="view-message text-end">May 14</td>
                                                            </tr>
                                                            <tr >
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star"></i></td>
                                                                <td class="view-message dont-show">LinkedIn </td>
                                                                <td class="view-message">Alireza: Nokia Networks, System Group and </td>
                                                                <td class="view-message inbox-small-cells"><i class="fa fa-paperclip"></i></td>
                                                                <td class="view-message text-end">February 25</td>
                                                            </tr>
                                                            <tr >
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star"></i></td>
                                                                <td class="dont-show">Facebook</td>
                                                                <td class="view-message view-message">Your account was recently logged into</td>
                                                                <td class="view-message inbox-small-cells"></td>
                                                                <td class="view-message text-end">March 14</td>
                                                            </tr>
                                                            <tr >
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star"></i></td>
                                                                <td class="view-message dont-show">Twitter</td>
                                                                <td class="view-message">Your Twitter password has been changed</td>
                                                                <td class="view-message inbox-small-cells"></td>
                                                                <td class="view-message text-end">April 07</td>
                                                            </tr>
                                                            <tr >
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star"></i></td>
                                                                <td class="view-message dont-show">InternetSeer Website Monitoring</td>
                                                                <td class="view-message">http://golddesigner.org/ Performance Report</td>
                                                                <td class="view-message inbox-small-cells"></td>
                                                                <td class="view-message text-end">July 14</td>
                                                            </tr>
                                                            <tr >
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star inbox-started"></i></td>
                                                                <td class="view-message dont-show">AddMe.com</td>
                                                                <td class="view-message">Submit Your Website to the AddMe Business Directory</td>
                                                                <td class="view-message inbox-small-cells"></td>
                                                                <td class="view-message text-end">August 10</td>
                                                            </tr>
                                                            <tr >
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star"></i></td>
                                                                <td class="view-message dont-show">Terri Rexer, S P N</td>
                                                                <td class="view-message view-message">Forget Google AdWords: Un-Limited Clicks fo</td>
                                                                <td class="view-message inbox-small-cells"><i class="fa fa-paperclip"></i></td>
                                                                <td class="view-message text-end">April 14</td>
                                                            </tr>
                                                            <tr >
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star"></i></td>
                                                                <td class="view-message dont-show">Bertina </td>
                                                                <td class="view-message">IMPORTANT: Don't lose your domains!</td>
                                                                <td class="view-message inbox-small-cells"><i class="fa fa-paperclip"></i></td>
                                                                <td class="view-message text-end">June 16</td>
                                                            </tr>
                                                            <tr >
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star inbox-started"></i></td>
                                                                <td class="view-message dont-show">Laura Gaffin, S P N </td>
                                                                <td class="view-message">Your Website On Google (Higher Rankings Are Better)</td>
                                                                <td class="view-message inbox-small-cells"></td>
                                                                <td class="view-message text-end">August 10</td>
                                                            </tr>
                                                            <tr >
                                                                <td class="inbox-small-cells">
                                                                    <label class="ckbox m-0"><input type="checkbox"> <span></span></label>
                                                                </td>
                                                                <td class="inbox-small-cells"><i class="fa fa-star"></i></td>
                                                                <td class="view-message dont-show">Facebook</td>
                                                                <td class="view-message view-message">Alireza Zare Login faild</td>
                                                                <td class="view-message inbox-small-cells"><i class="fa fa-paperclip"></i></td>
                                                                <td class="view-message text-end">feb 14</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <ul class="pagination float-end mb-5">
                                            <li class="page-item page-prev disabled">
                                                <a class="page-link" href="javascript:void(0);" tabindex="-1">Prev</a>
                                            </li>
                                            <li class="page-item active"><a class="page-link" href="javascript:void(0);">1</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">4</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">5</a></li>
                                            <li class="page-item page-next">
                                                <a class="page-link" href="javascript:void(0);">Next</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div><!-- COL-END -->
                            </div>
                            <!-- ROW-1 CLOSED -->

@endsection

@section('scripts')



@endsection
