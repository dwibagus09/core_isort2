
			<?php if(!empty($this->msg)) echo '<div class="msg">'.$this->msg.'</div>'; ?>
			  <div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
				  	<div class="dashboard">
						<div class="col-md-1 col-sm-3 col-xs-4">
							<?php if($this->totalSecIssues['total'] > 0) { ?><span class="notify-bubble"><?php echo $this->totalSecIssues['total']; ?></span><?php } ?>
							<div class="menu-icon">
								<a href="/default/issue/listissues/category/1">
									<div class="icon-img"><img src="/images/security_opened_issues.png" /></div>
									<div class="icon-title">Security<br/>Opened&nbsp;Issues&nbsp;</div>
								</a>	
							</div>
						</div>		

						<div class="col-md-1 col-sm-3 col-xs-4">
							<div class="menu-icon">
								<?php if($this->totalSafIssues['total'] > 0) { ?><span class="notify-bubble"><?php echo $this->totalSafIssues['total']; ?></span><?php } ?>
								<a href="/default/issue/listissues/category/3">
									<div class="icon-img"><img src="/images/safety_opened_issues.png" /></div>
									<div class="icon-title">Safety<br/>Opened&nbsp;Issues&nbsp;</div>
								</a>	
							</div>
						</div>

						<div class="col-md-1 col-sm-3 col-xs-4">
							<?php if($this->totalParkIssues['total'] > 0) { ?><span class="notify-bubble"><?php echo $this->totalParkIssues['total']; ?></span><?php } ?>
							<div class="menu-icon">
								<a href="/default/issue/listissues/category/5">
									<div class="icon-img"><img src="/images/parking_opened_issues.png" /></div>
									<div class="icon-title">Parking &amp; Traffic<br/>Opened&nbsp;Issues&nbsp;</div>
								</a>	
							</div>
						</div>

						<div class="col-md-1 col-sm-3 col-xs-4">
							<?php if($this->totalHKIssues['total'] > 0) { ?><span class="notify-bubble"><?php echo $this->totalHKIssues['total']; ?></span><?php } ?>
							<div class="menu-icon">
								<a href="/default/issue/listissues/category/2">
									<div class="icon-img"><img src="/images/housekeeping_opened_issues.png" /></div>
									<div class="icon-title">Housekeeping<br/>Opened&nbsp;Issues&nbsp;</div>
								</a>	
							</div>
						</div>	

						<div class="col-md-1 col-sm-3 col-xs-4">
							<?php if($this->totalEngIssues['total'] > 0) { ?><span class="notify-bubble"><?php echo $this->totalEngIssues['total']; ?></span><?php } ?>
							<div class="menu-icon">
								<a href="/default/issue/listissues/category/6">
									<div class="icon-img"><img src="/images/engineering_opened_issues.png" /></div>
									<div class="icon-title">Engineering<br/>Opened&nbsp;Issues&nbsp;</div>
								</a>	
							</div>
						</div>	

						<div class="col-md-1 col-sm-3 col-xs-4">
							<?php if($this->totalBSIssues['total'] > 0) { ?><span class="notify-bubble"><?php echo $this->totalBSIssues['total']; ?></span><?php } ?>
							<div class="menu-icon">
								<a href="/default/issue/listissues/category/10">
									<div class="icon-img"><img src="/images/bs_opened_issues.png" /></div>
									<div class="icon-title">Building Service<br/>Opened&nbsp;Issues&nbsp;</div>
								</a>	
							</div>
						</div>	
                        <?php /*
						<div class="col-md-1 col-sm-3 col-xs-4">
							<?php if($this->totalTRIssues['total'] > 0) { ?><span class="notify-bubble"><?php echo $this->totalTRIssues['total']; ?></span><?php } ?>
							<div class="menu-icon">
								<a href="/default/issue/listissues/category/11">
									<div class="icon-img"><img src="/images/tr_opened_issues.png" /></div>
									<div class="icon-title">Tenant Relation<br/>Opened&nbsp;Issues&nbsp;</div>
								</a>	
							</div>
						</div>	*/ ?>					
						<div class="clearfix"></div>
					</div>
          		</div>
			</div>
        </div>
        <!-- /page content -->

<script type="text/javascript">
$(document).ready(function() {
	$(".menu-icon a").click(function() {
		$("#"+this.dataset.d+"-star").css( "display", "none" );
	});

});	
</script>