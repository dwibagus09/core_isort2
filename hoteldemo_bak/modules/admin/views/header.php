<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <!--IE Compatibility modes-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--Mobile first-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Hotel Demo - iSort Administrator</title>
    
    <meta name="description" content="Hotel Demo - iSort Administrator">
    <meta name="author" content="">
    
    <meta name="msapplication-TileColor" content="#5bc0de" />
    <meta name="msapplication-TileImage" content="/images/isort_new_logo.png" />
	
	<link rel="icon" href="/images/isort_new_logo.png" type="image/png" />
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.css">
    
    <!-- Font Awesome -->
    <link href="/fontawesome5.15.3/css/all.css" rel="stylesheet">
    
    <!-- Metis core stylesheet -->
    <link rel="stylesheet" href="/css/main.css">
    
    <!-- metisMenu stylesheet -->
    <link rel="stylesheet" href="/metismenu/metisMenu.css">
    
    <!-- onoffcanvas stylesheet -->
    <link rel="stylesheet" href="/onoffcanvas/onoffcanvas.css">
    
    <!-- animate.css stylesheet -->
    <link rel="stylesheet" href="/animate.css/animate.css">

	<!-- style.css stylesheet -->
    <link rel="stylesheet" href="/css/style.css">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

	<!--jQuery -->
	<script src="/jquery/jquery.js"></script>


	<!--Bootstrap -->
	<script src="/bootstrap/js/bootstrap.js"></script>
	<!-- MetisMenu -->
	<script src="/metismenu/metisMenu.js"></script>
	<!-- onoffcanvas -->
	<script src="/onoffcanvas/onoffcanvas.js"></script>


	<!-- Metis core scripts -->
	<script src="/js/core.js"></script>
	<!-- Metis demo scripts -->
	<script src="/js/app.js"></script>

  </head>

        <body class="  ">
            <div class="bg-dark dk" id="wrap">
                <div id="top">
                    <!-- .navbar -->
                    <nav class="navbar navbar-inverse navbar-static-top">
                        <div class="container-fluid">
                    
                    
                            <!-- Brand and toggle get grouped for better mobile display -->
                            <header class="navbar-header">
                    
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a href="index.html" class="navbar-brand"><img src="/images/logo.png" alt="" width="40" style="margin:5px;"></a>
                    
                            </header>
                    
                    
                    
                            <div class="topnav">
                                <div class="btn-group">
                                    <a href="/admin/user/logout" data-toggle="tooltip" data-original-title="Logout" data-placement="bottom"
                                       class="btn btn-metis-1 btn-sm">
                                        <i class="fa fa-power-off"></i>
                                    </a>
                                </div>                                
                    
                            </div>
                    
                    
                    
                    
                            <div class="collapse navbar-collapse navbar-ex1-collapse">
									<h1 style="font-size:24px; margin-top:10px; padding-left:10px; float:left">Hotel Demo iSort Administrator</h1>	
									<div style="float: right; padding: 15px;"><?php echo "Welcome ".$this->username; ?></div>
                            </div>
                        </div>
                        <!-- /.container-fluid -->
                    </nav>
                    <!-- /.navbar -->
                </div>
                <!-- /#top -->
                    <div id="left">
                        <!-- #menu -->
                        <ul id="menu" class="bg-blue dker">
                                  <li id="menu-dashboard" class="">
                                    <a href="/admin/index/index">
                                      <i class="fas fa-th"></i><span class="link-title">&nbsp;Dashboard</span>
                                    </a>
                                  </li>
							<?php if($this->site_id > 0) { ?>
                                  <li id="menu-users" class="">
                                    <a href="/admin/user/view"  aria-expanded="false">
                                      <i class="fas fa-users "></i>
                                      <span class="link-title">Users</span>
                                    </a>
                                  </li> 
								  <li id="menu-area" class="">
                                    <a href="/admin/area/view"  aria-expanded="false">
                                      <i class="fas fa-map-marker-alt"></i>
                                      <span class="link-title">Area</span>
                                    </a>
                                  </li> 
								  <?php /*<li class="">
                                    <a href="/admin/user/viewrole">
                                      <i class="fa fa-suitcase "></i>
                                      <span class="link-title">User Role</span>
                                    </a>
                                  </li> */ ?>
								  <?php if($this->site_id == 2) { ?>
								  <li id="menu-security" class="">
									  <a href="javascript:;"  class="has-arrow"   aria-expanded="false">
										<i class="fas fa-shield-alt "></i>
										<span class="link-title">Security, Safety, Parking</span>
									  </a>
										<ul class="collapse">	
										  <li class="incident">
											<a href="/admin/issuefinding/viewsecuritykejadian">
											  <i class="fa fa-cog "></i>
											  <span class="link-title">Incident</span>
											</a>
										  </li>			
										  <li class="modus">
											<a href="/admin/issuefinding/viewsecuritymodus">
											  <i class="fas fa-cog "></i>
											  <span class="link-title">Modus</span>
											</a>
										  </li>	
										  <li class="floor">
											<a href="/admin/issuefinding/viewsecurityfloor">
											  <i class="fas fa-cog "></i>
											  <span class="link-title">Floor</span>
											</a>
										  </li>	
										  <li class="ap-module">
											<a href="/admin/actionplan/viewmodule/c/1">
											  <i class="fas fa-cog "></i>
											  <span class="link-title">Action Plan Module</span>
											</a>
										  </li>	
										  <li class="ap-target">
											<a href="/admin/actionplan/viewtarget/c/1">
											  <i class="fas fa-cog "></i>
											  <span class="link-title">Action Plan Target</span>
											</a>
										  </li>	
										  <li class="ap-activity">
											<a href="/admin/actionplan/viewactivity/c/1">
											  <i class="fas fa-cog "></i>
											  <span class="link-title">Action Plan Activity</span>
											</a>
										  </li>	
										  <li class="ap-reminder-email">
											<a href="/admin/actionplan/reminder/c/1">
											  <i class="fas fa-cog "></i>
											  <span class="link-title">Action Plan Reminder Email</span>
											</a>
										  </li>					  
										</ul>
								  </li>
								  <?php /*<li id="menu-safety" class="">
                                    <a href="javascript:;"  class="has-arrow"   aria-expanded="false">
                                      <i class="fas fa-hard-hat"></i>
                                      <span class="link-title">Safety</span>
                                    </a>
									<ul class="collapse">
                                      <li>
                                        <a href="/admin/equipment/viewbuildingprotectionequipmenttype">
                                          <i class="fas fa-cog"></i>&nbsp; Builder Protection Equipment Type </a>
                                      </li>
                                      <li>
                                        <a href="/admin/equipment/viewbuildingprotectionequipment">
                                          <i class="fas fa-cog"></i>&nbsp; Building Protection Equipment </a>
                                      </li>
                                      <li>
                                        <a href="/admin/equipment/viewfireaccidentequipment">
                                          <i class="fas fa-cog"></i>&nbsp; Perlengkapan Penanggulangan Kebakaran dan Kecelakaan Gedung </a>
                                      </li>
                                      <li class="incident">
                                      <a href="/admin/issuefinding/viewsafetykejadian">
                                        <i class="fas fa-cog "></i>
                                        <span class="link-title">Incident</span>
                                      </a>
                                    </li>			
                                    <li class="modus">
                                      <a href="/admin/issuefinding/viewsafetymodus">
                                        <i class="fas fa-cog "></i>
                                        <span class="link-title">Modus</span>
                                      </a>
                                    </li>	
                                    <li class="floor">
                                      <a href="/admin/issuefinding/viewsafetyfloor">
                                        <i class="fas fa-cog "></i>
                                        <span class="link-title">Floor</span>
                                      </a>
                                    </li>	
                                    <li class="ap-module">
                                      <a href="/admin/actionplan/viewmodule/c/3">
                                        <i class="fas fa-cog "></i>
                                        <span class="link-title">Action Plan Module</span>
                                      </a>
                                    </li>	
                                    <li class="ap-target">
                                      <a href="/admin/actionplan/viewtarget/c/3">
                                        <i class="fas fa-cog "></i>
                                        <span class="link-title">Action Plan Target</span>
                                      </a>
                                    </li>	
                                    <li class="ap-activity">
                                      <a href="/admin/actionplan/viewactivity/c/3">
                                        <i class="fas fa-cog "></i>
                                        <span class="link-title">Action Plan Activity</span>
                                      </a>
                                    </li>	
                                    <li class="ap-reminder-email">
                                        <a href="/admin/actionplan/reminder/c/3">
                                          <i class="fas fa-cog "></i>
                                          <span class="link-title">Action Plan Reminder Email</span>
                                        </a>
                                      </li>	
                                    </ul>
                                  </li> */ ?>
								  <li id="menu-parking" class="">
                                    <a href="javascript:;"  class="has-arrow"   aria-expanded="false">
                                      <i class="fas fa-parking"></i>
                                      <span class="link-title">Fit Out</span>
                                    </a>
									<ul class="collapse">
                                      <li class="incident">
                                        <a href="/admin/issuefinding/viewparkingkejadian">
                                          <i class="fas fa-cog "></i>
                                          <span class="link-title">Incident</span>
                                        </a>
                                      </li>			
                                      <li class="modus">
                                        <a href="/admin/issuefinding/viewparkingmodus">
                                          <i class="fas fa-cog "></i>
                                          <span class="link-title">Modus</span>
                                        </a>
                                      </li>	
                                      <li class="floor">
                                        <a href="/admin/issuefinding/viewparkingfloor">
                                          <i class="fas fa-cog "></i>
                                          <span class="link-title">Floor</span>
                                        </a>
                                      </li>	
                                      <li class="ap-module">
                                        <a href="/admin/actionplan/viewmodule/c/5">
                                          <i class="fas fa-cog "></i>
                                          <span class="link-title">Action Plan Module</span>
                                        </a>
                                      </li>	
                                      <li class="ap-target">
                                        <a href="/admin/actionplan/viewtarget/c/5">
                                          <i class="fas fa-cog "></i>
                                          <span class="link-title">Action Plan Target</span>
                                        </a>
                                      </li>	
                                      <li class="ap-activity">
                                        <a href="/admin/actionplan/viewactivity/c/5">
                                          <i class="fas fa-cog "></i>
                                          <span class="link-title">Action Plan Activity</span>
                                        </a>
                                      </li>	
                                      <li class="ap-reminder-email">
                                        <a href="/admin/actionplan/reminder/c/5">
                                          <i class="fas fa-cog "></i>
                                          <span class="link-title">Action Plan Reminder Email</span>
                                        </a>
                                      </li>	
                                    </ul>
                                  </li>
								<?php } ?>						  
								  <li id="menu-housekeeping" class="">
                                    <a href="javascript:;"  class="has-arrow"   aria-expanded="false">
                                      <i class="fas fa-broom"></i>
                                      <span class="link-title">Housekeeping</span>
                                    </a>
									<ul class="collapse">
                                      <?php /*<li>
                                        <a href="/admin/setting/viewhousekeepingreportingtime">
                                          <i class="fas fa-cog"></i>&nbsp; Reporting Time </a>
                                      </li>
									  <li>
                                        <a href="/admin/tangkapan/viewhousekeepinghasiltangkapan">
                                          <i class="fas fa-cog"></i>&nbsp; Hasil Tangkapan </a>
                                      </li> */ ?>
									<li class="incident">
                                        <a href="/admin/issuefinding/viewhousekeepingkejadian">
                                          <i class="fas fa-cog "></i>
                                          <span class="link-title">Incident</span>
                                        </a>
                                      </li>			
                                      <li class="modus">
                                        <a href="/admin/issuefinding/viewhousekeepingmodus">
                                          <i class="fas fa-cog "></i>
                                          <span class="link-title">Modus</span>
                                        </a>
                                      </li>	
                                      <li class="floor">
                                        <a href="/admin/issuefinding/viewhousekeepingfloor">
                                          <i class="fas fa-cog "></i>
                                          <span class="link-title">Floor</span>
                                        </a>
                                      </li>	
                                    <li class="ap-module">
                                    <a href="/admin/actionplan/viewmodule/c/2">
                                      <i class="fas fa-cog "></i>
                                      <span class="link-title">Action Plan Module</span>
                                    </a>
                                  </li>	
                                  <li class="ap-target">
                                    <a href="/admin/actionplan/viewtarget/c/2">
                                      <i class="fas fa-cog "></i>
                                      <span class="link-title">Action Plan Target</span>
                                    </a>
                                  </li>	
                                  <li class="">
                                    <a href="/admin/actionplan/viewactivity/c/2">
                                      <i class="fas fa-cog "></i>
                                      <span class="link-title">Action Plan Activity</span>
                                    </a>
                                  </li>	
                                  <li class="ap-reminder-email">
                                    <a href="/admin/actionplan/reminder/c/2">
                                      <i class="fas fa-cog "></i>
                                      <span class="link-title">Action Plan Reminder Email</span>
                                    </a>
                                  </li>	
                                    </ul>
                                  </li>
								  
								  <li id="menu-engineering" class="">
                                    <a href="javascript:;"  class="has-arrow"   aria-expanded="false">
                                      <i class="fas fa-tools"></i>
                                      <span class="link-title">Engineering</span>
                                    </a>
									<ul class="collapse">
                                      <?php /*<li>
                                        <a href="/admin/setting/viewhousekeepingreportingtime">
                                          <i class="fas fa-cog"></i>&nbsp; Reporting Time </a>
                                      </li>
									  <li>
                                        <a href="/admin/tangkapan/viewhousekeepinghasiltangkapan">
                                          <i class="fas fa-cog"></i>&nbsp; Hasil Tangkapan </a>
                                      </li> */ ?>
									<li class="incident">
                                        <a href="/admin/issuefinding/viewengineeringkejadian">
                                          <i class="fas fa-cog "></i>
                                          <span class="link-title">Incident</span>
                                        </a>
                                      </li>			
                                      <li class="modus">
                                        <a href="/admin/issuefinding/viewengineeringmodus">
                                          <i class="fas fa-cog "></i>
                                          <span class="link-title">Modus</span>
                                        </a>
                                      </li>	
                                      <li class="floor">
                                        <a href="/admin/issuefinding/viewengineeringfloor">
                                          <i class="fas fa-cog "></i>
                                          <span class="link-title">Floor</span>
                                        </a>
                                      </li>	
                                    <li class="ap-module">
                                    <a href="/admin/actionplan/viewmodule/c/6">
                                      <i class="fas fa-cog "></i>
                                      <span class="link-title">Preventive Maintenance Module</span>
                                    </a>
                                  </li>	
                                  <li class="ap-target">
                                    <a href="/admin/actionplan/viewtarget/c/6">
                                      <i class="fas fa-cog "></i>
                                      <span class="link-title">Preventive Maintenance Target</span>
                                    </a>
                                  </li>	
                                  <li class="ap-activity" class="">
                                    <a href="/admin/actionplan/viewactivity/c/6">
                                      <i class="fas fa-cog "></i>
                                      <span class="link-title">Preventive Maintenance Activity</span>
                                    </a>
                                  </li>	
                                  <li class="ap-reminder-email">
                                    <a href="/admin/actionplan/reminder/c/6">
                                      <i class="fas fa-cog "></i>
                                      <span class="link-title">Preventive Maintenance Reminder Email</span>
                                    </a>
                                  </li>								  
								</ul>
							  </li>
							  
							  <?php if($this->site_id == 2) { ?>
								  <li id="menu-tenantrelation" class="">
									  <a href="javascript:;"  class="has-arrow"   aria-expanded="false">
										<i class="fas fa-store "></i>
										<span class="link-title">Tenant Relation</span>
									  </a>
										<ul class="collapse">	
										  <li class="incident">
											<a href="/admin/issuefinding/viewtenantrelationkejadian">
											  <i class="fa fa-cog "></i>
											  <span class="link-title">Incident</span>
											</a>
										  </li>			
										  <li class="modus">
											<a href="/admin/issuefinding/viewtenantrelationmodus">
											  <i class="fas fa-cog "></i>
											  <span class="link-title">Modus</span>
											</a>
										  </li>	
										  <li class="floor">
											<a href="/admin/issuefinding/viewtenantrelationfloor">
											  <i class="fas fa-cog "></i>
											  <span class="link-title">Floor</span>
											</a>
										  </li>				  
										</ul>
								  </li>
								  <?php } ?>
								  <?php /* if($this->site_id == 1) { ?>
									<li id="menu-16" class="">
										<a href="javascript:;"  class="has-arrow"   aria-expanded="false">
										  <i class="fas fa-phone"></i>
										  <span class="link-title">Guest Complain</span>
										</a>
										<ul class="collapse">
										<li class="incident">
											<a href="/admin/issuefinding/viewkejadian/cat_id/16">
											  <i class="fas fa-cog "></i>
											  <span class="link-title">Incident</span>
											</a>
										  </li>			
										  <li class="modus">
											<a href="/admin/issuefinding/viewmodus/cat_id/16">
											  <i class="fas fa-cog "></i>
											  <span class="link-title">Modus</span>
											</a>
										  </li>	
										  <li class="floor">
											<a href="/admin/issuefinding/viewfloor/cat_id/16">
											  <i class="fas fa-cog "></i>
											  <span class="link-title">Floor</span>
											</a>
										  </li>	
										</ul>
									  </li>
								  <?php } */ ?>
								  <li id="menu-buildingservice" class="">
                                    <a href="javascript:;"  class="has-arrow"   aria-expanded="false">
                                      <i class="fas fa-address-book"></i>
                                      <span class="link-title">Human Operations</span>
                                    </a>
									<ul class="collapse">
									<li class="incident">
                                        <a href="/admin/issuefinding/viewbuildingservicekejadian">
                                          <i class="fas fa-cog "></i>
                                          <span class="link-title">Incident</span>
                                        </a>
                                      </li>			
                                      <li class="modus">
                                        <a href="/admin/issuefinding/viewbuildingservicemodus">
                                          <i class="fas fa-cog "></i>
                                          <span class="link-title">Modus</span>
                                        </a>
                                      </li>	
                                      <li class="floor">
                                        <a href="/admin/issuefinding/viewbuildingservicefloor">
                                          <i class="fas fa-cog "></i>
                                          <span class="link-title">Floor</span>
                                        </a>
                                      </li>	
                                    </ul>
                                  </li>
								  <?php if($this->site_id == 1) { ?>
								  <li id="menu-digital-checklist" class="">
                                    <a href="#"  class="has-arrow"   aria-expanded="false">
                                      <i class="fas fa-clipboard-list"></i>
                                      <span class="link-title">Digital Checklist</span>
                                    </a>
									<ul class="collapse">
									<li class="checklist-cat">
                                        <a href="/admin/checklist/categories">
                                          <i class="fas fa-cog "></i>
                                          <span class="link-title">Categories</span>
                                        </a>
                                      </li>			
                                      <li class="checklist-subcat">
                                        <a href="/admin/checklist/subcategories">
                                          <i class="fas fa-cog "></i>
                                          <span class="link-title">Sub-categories</span>
                                        </a>
                                      </li>	
                                      <li class="checklist-templates">
                                        <a href="/admin/checklist/view">
                                          <i class="fas fa-cog "></i>
                                          <span class="link-title">Templates</span>
                                        </a>
                                      </li>	
                                    </ul>
                                  </li>
								  <?php } } ?>
								  <li id="menu-logout" class="">
                                    <a href="/admin/user/logout"  aria-expanded="false">
                                      <i class="fas fa-door-open "></i>
                                      <span class="link-title">Logout</span>
                                    </a>
                                  </li> 
                                </ul>
                        <!-- /#menu -->
                    </div>
                    <!-- /#left -->
