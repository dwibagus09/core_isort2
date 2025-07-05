
<?php if(!empty($this->msg)) echo '<div class="msg">'.$this->msg.'</div>'; ?>
  <div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="dashboard">
			<h1 class="page-title">Statistic</h1>
			<?php if($this->showStatistic == 1) { ?>
				<div class="col-md-1 col-sm-3 col-xs-4">	
					<div class="submenu-icon">
						<a href="/default/statistic/view">
							<img id="statistic-icon" src="/images/site_analytics.png" />
							<div class="submenu-icon-title">Kaizen Statistic</div>	
						</a>
					</div>
				</div>	
				<div class="col-md-1 col-sm-3 col-xs-4">	
					<div class="submenu-icon">
						<a href="/default/statistic/site">
							<img id="statistic-icon" src="/images/chief_report.png" />
							<div class="submenu-icon-title">User Statistic</div>	
						</a>
					</div>
				</div>	
				<?php if(count($this->sitesSelections) > 1) { ?>
				<div class="col-md-1 col-sm-3 col-xs-4">	
					<div class="submenu-icon">
						<a href="/default/statistic/corporate">
							<img id="statistic-icon" src="/images/corp_analytics.png" />
							<div class="submenu-icon-title">Corporate Statistic</div>	
						</a>
					</div>
				</div>
				<?php } ?>	
				<?php /*<div class="col-md-1 col-sm-3 col-xs-4">	
					<div class="submenu-icon">
						<a href="/default/statistic/workorder">
							<img id="statistic-icon" src="/images/corp_analytics.png" />
							<div class="submenu-icon-title">Work Order Statistic</div>	
						</a>
					</div>
				</div> */ ?>
				<?php } ?>	
			<div class="clearfix"></div>
		</div>
	</div>
</div>
</div>
<!-- /page content -->
		
		
<script type="text/javascript">
$(document).ready(function() {
	$("#statistic-menu").addClass('active');
	$("#statistic-menu .child_menu").show();
});	
</script>