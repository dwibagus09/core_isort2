
			<?php if(!empty($this->msg)) echo '<div class="msg">'.$this->msg.'</div>'; ?>
			  <div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
				  	<div class="dashboard">
						<?php if($this->showSecurityKpi == 1) { ?>
							<div class="col-md-1 col-sm-3 col-xs-4">	
								<div class="menu-icon">
									<?php if($this->securityKPI > 0) { ?><span class="notify-bubble2"><?php echo $this->securityKPI; ?></span><?php } ?>
									<a href="/default/kpi/view/c/1">
										<div class="icon-img"><img id="statistic-icon" src="/images/kpi_security.png" /></div>
										<div class="icon-title">New Security KPI Month to Year</div>	
									</a>
								</div>
							</div>	
							<div class="col-md-1 col-sm-3 col-xs-4">	
								<div class="menu-icon">
									<?php if($this->monthlySecurityKPI > 0) { ?><span class="notify-bubble2"><?php echo $this->monthlySecurityKPI; ?></span><?php } ?>
									<a href="/default/kpi/viewmonthly/c/1">
										<div class="icon-img"><img id="statistic-icon" src="/images/kpi_monthly_security.png" /></div>
										<div class="icon-title">Monthly Security KPI</div>	
									</a>
								</div>
							</div>	
							<?php } ?>
							<?php if($this->showSafetyKpi == 1) { ?>
							<div class="col-md-1 col-sm-3 col-xs-4">
								<?php if($this->safetyKPI > 0) { ?><span class="notify-bubble2"><?php echo $this->safetyKPI; ?></span><?php } ?>	
								<div class="menu-icon">
									<a href="/default/kpi/view/c/3">
										<div class="icon-img"><img id="statistic-icon" src="/images/kpi_safety.png" /></div>
										<div class="icon-title">New Safety KPI Month to Year</div>	
									</a>
								</div>
							</div>	
							<div class="col-md-1 col-sm-3 col-xs-4">	
								<?php if($this->monthlySafetyKPI > 0) { ?><span class="notify-bubble2"><?php echo $this->monthlySafetyKPI; ?></span><?php } ?>
								<div class="menu-icon">
									<a href="/default/kpi/viewmonthly/c/3">
										<div class="icon-img"><img id="statistic-icon" src="/images/kpi_monthly_safety.png" /></div>
										<div class="icon-title">Monthly Safety KPI</div>	
									</a>
								</div>
							</div>	
							<?php } ?>
							<?php if($this->showParkingKpi == 1) { ?>
							<div class="col-md-1 col-sm-3 col-xs-4">	
								<?php if($this->parkingKPI > 0) { ?><span class="notify-bubble2"><?php echo $this->parkingKPI; ?></span><?php } ?>
								<div class="menu-icon">
									<a href="/default/kpi/view/c/5">
										<div class="icon-img"><img id="statistic-icon" src="/images/kpi_parking.png" /></div>
										<div class="icon-title">New Parking &amp; Traffic KPI Month to Year</div>	
									</a>
								</div>
							</div>	
							<div class="col-md-1 col-sm-3 col-xs-4">	
								<?php if($this->monthlyParkingKPI > 0) { ?><span class="notify-bubble2"><?php echo $this->monthlyParkingKPI; ?></span><?php } ?>
								<div class="menu-icon">
									<a href="/default/kpi/viewmonthly/c/5">
										<div class="icon-img"><img id="statistic-icon" src="/images/kpi_monthly_parking.png" /></div>
										<div class="icon-title">Monthly Parking &amp; Traffic KPI</div>	
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
