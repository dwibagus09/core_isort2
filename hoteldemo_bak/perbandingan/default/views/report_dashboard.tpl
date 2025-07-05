
			<?php if(!empty($this->msg)) echo '<div class="msg">'.$this->msg.'</div>'; ?>
			  <div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
				  	<div class="dashboard">
						<?php if($this->showSecurity == 1) { ?>
						<div class="col-md-1 col-sm-3 col-xs-4">
							<img id="security-star" class="unread-flag" src="/images/star2.png" <?php if(!$this->showSecurityStarNotif) { echo 'style="display:none;"'; } ?> />	
							<div class="menu-icon">
								<?php if($this->isMobile == true) {
									if($this->showChiefSecurity != 1) $sec_url = 'view'; 
									else $sec_url = 'viewchiefreport';
								}
								else {
									$sec_url = "viewchiefdetailreport/dt/".date("Y-m-d");
								} ?>
								<a href="/default/security/<?php echo $sec_url; ?>"  data-d="security">
									<div class="icon-img"><img id="security-icon" src="/images/security_report2.png" /></div>
									<div class="icon-title">Security Report</div>	
								</a>
							</div>
						</div>	
						<?php } ?>
						<?php if($this->showSafety == 1) { ?>
						<div class="col-md-1 col-sm-3 col-xs-4">	
							<img id="safety-star" class="unread-flag" src="/images/star2.png" <?php if(!$this->showSafetyStarNotif) { echo 'style="display:none;"'; } ?> />
							<div class="menu-icon">
								<?php if($this->isMobile == true) {
									$saf_url = 'viewreport';
								}
								else {
									if(empty($this->safetyReport['report_id'])) $saf_url = "viewreport";
									else $saf_url = "viewdetailreport/id/".$this->safetyReport['report_id'];
								} ?>
								<a href="/default/safety/<?php echo $saf_url; ?>"  data-d="safety">
									<div class="icon-img"><img id="security-icon" src="/images/safety_report2.png" /></div>
									<div class="icon-title">Safety Report</div>	
								</a>
							</div>
						</div>	
						<?php } ?>
						<?php if($this->showParkingTraffic == 1) { ?>
						<div class="col-md-1 col-sm-3 col-xs-4">	
							<img id="parking-star" class="unread-flag" src="/images/star2.png" <?php if(!$this->showParkingStarNotif) { echo 'style="display:none;"'; } ?> />
							<div class="menu-icon">
								<?php if($this->isMobile == true) {
									$park_url = 'viewreport';
								}
								else {
									if(empty($this->parkingReport['parking_report_id'])) $park_url = "viewreport";
									else $park_url = "viewdetailreport/id/".$this->parkingReport['parking_report_id'];
								} ?>
								<a href="/default/parking/<?php echo $park_url; ?>"  data-d="parking">
									<div class="icon-img"><img id="parking-icon" src="/images/parking_report2.png" /></div>
									<div class="icon-title">Parking &amp; Traffic Report</div>	
								</a>
							</div>
						</div>	
						<?php } ?>
						<?php if($this->showHousekeeping == 1) { ?>
						<div class="col-md-1 col-sm-3 col-xs-4">	
							<img id="hk-star" class="unread-flag" src="/images/star2.png" <?php if(!$this->showHousekeepingStarNotif) { echo 'style="display:none;"'; } ?> />
							<div class="menu-icon">
								<?php if($this->isMobile == true) {
									$hk_url = 'viewreport';
								}
								else {
									if(empty($this->housekeepingReport['housekeeping_report_id'])) $hk_url = "viewreport";
									else $hk_url = "viewdetailreport/id/".$this->housekeepingReport['housekeeping_report_id'];
								} ?>
								<a href="/default/housekeeping/<?php echo $hk_url; ?>"  data-d="hk">
									<div class="icon-img"><img id="housekeeping-icon" src="/images/housekeeping_report2.png" /></div>
									<div class="icon-title">Housekeeping Report</div>	
								</a>
							</div>
						</div>	
						<?php } ?>
						<?php if($this->showOM == 1) { ?>
						<div class="col-md-1 col-sm-3 col-xs-4">	
							<img id="om-star" class="unread-flag" src="/images/star2.png" <?php if(!$this->showOMStarNotif) { echo 'style="display:none;"'; } ?> />
							<div class="menu-icon">
								<?php if($this->isMobile == true) {
									$om_url = 'viewreport';
								}
								else {
									if(empty($this->omReport['operation_mall_report_id'])) $om_url = "viewreport";
									else $om_url = "viewdetailreport/id/".$this->omReport['operation_mall_report_id'];
								} ?>
								<a href="/default/operational/<?php echo $om_url; ?>"  data-d="om" >
									<div class="icon-img"><img id="operational-mal-icon" src="/images/om_report2.png" /></div>
									<div class="icon-title">Operational Mall Report</div>	
								</a>
							</div>
						</div>	
						<?php } ?>
						<?php if($this->showMod == 1) { ?>
						<div class="col-md-1 col-sm-3 col-xs-4">	
							<img id="mod-star" class="unread-flag" src="/images/star2.png" <?php if(!$this->showMODstarNotif) { echo 'style="display:none;"'; } ?> />
							<div class="menu-icon">
								<?php if($this->isMobile == true) {
									$mod_url = 'viewreport';
								}
								else {
									if(empty($this->modReport['mod_report_id'])) $mod_url = "viewreport";
									else $mod_url = "viewdetailreport/id/".$this->modReport['mod_report_id'];
								} ?>
								<a href="/default/mod/<?php echo $mod_url; ?>"  data-d="mod">
									<div class="icon-img"><img id="mod-icon" src="/images/mod_report2.png" /></div>
									<div class="icon-title">Manager On Duty Report</div>	
								</a>
							</div>
						</div>	
						<?php } ?>
						<?php if($this->showBM == 1) { ?>
						<div class="col-md-1 col-sm-3 col-xs-4">
							<img id="bm-star" class="unread-flag" src="/images/star2.png" <?php if(!$this->showBMStarNotif) { echo 'style="display:none;"'; } ?> />	
							<div class="menu-icon">
								<?php if($this->isMobile == true) {
									$bm_url = 'viewreport';
								}
								else {
									if(empty($this->bmReport['report_id'])) $bm_url = "viewreport";
									else $bm_url = "exporttopdf/id/".$this->bmReport['report_id'];
								} ?>
								<a href="/default/bm/<?php echo $bm_url; ?>" data-d="bm" <?php if($this->isMobile == false && !empty($this->bmReport['report_id'])) echo 'target="_blank"'; ?>>
									<div class="icon-img"><img id="bm-icon" src="/images/bm_report2.png" /></div>
									<div class="icon-title">Building Manager Report</div>	
								</a>
							</div>
						</div>	
						<?php } ?>			
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
