<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<h3 class="page-title">Reporting Time</h3>
				<br/>
				 <form action="/admin/setting/saveothersetting" id="equipment-form">
					<input type="hidden" name="setting_id" id="setting_id" value="<?php echo $this->setting['setting_id']; ?>" />
					<input type="hidden" name="act" id="act" value="<?php echo $this->setting['act']; ?>" />
					<label for="parking_traffic_reporting_time">Parking &amp; Traffic Reporting Time</label><br/>
					<input type="text" class="form-control" name="parking_traffic_reporting_time" id="parking_traffic_reporting_time" value="<?php echo $this->setting['parking_traffic_reporting_time']; ?>">
					<br/>
					<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 0px; width: 100px;">
				  </form>
				  <br/>
			</div>
		</div>
		<!-- /.inner -->
	</div>
	<!-- /.outer -->
</div>
<!-- /#content -->