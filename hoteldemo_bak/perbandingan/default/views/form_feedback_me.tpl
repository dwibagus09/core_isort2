<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

  <div class="">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<?php if(!empty($this->message)) { ?><div class="err-msg"><?php echo $this->message; ?></div><?php } ?>
		  <form id="feedback-form" class="form-label-left" action="/default/feedback/send" method="POST">
			<div class="x_title">
				<h2>Feedback</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div style="font-size:13px; font-weight:bold;">
					Help us to improve... We value your opinion
				</div>
				<br/>
				<br/>
				<div class="item form-group">
					<div style="font-weight:bold;">Module that need to be improve?</div>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<select id="module_menu" name="module_menu" class="form-control" required>
							<option disabled selected value style="display:none"> -- select module -- </option>
							<option value="Issue Finding">Issue Finding</option>
							<?php if($this->showSecurity || $this->showChiefSecurity) { ?><option value="Security">Security</option><?php } ?>
							<?php if($this->showSafety) { ?><option value="Safety">Safety</option><?php } ?>
							<?php if($this->showParkingTraffic) { ?><option value="Parking & Traffic">Parking & Traffic</option><?php } ?>
							<?php if($this->showHousekeeping) { ?><option value="Housekeeping">Housekeeping</option><?php } ?>
							<?php if($this->showOM) { ?><option value="Operational Mall">Operational Mall</option><?php } ?>
							<?php if($this->showMod) { ?><option value="Manager On Duty">Manager On Duty</option><?php } ?>
							<?php if($this->showHODMeeting) { ?><option value="HOD Meeting">HOD Meeting</option><?php } ?>
							<?php if($this->showITMeeting) { ?><option value="IT Meeting">IT Meeting</option><?php } ?>
							<?php if($this->showStatistic) { ?><option value="Statistic">Statistic</option><?php } ?>
							<option value="Feedback Me">Feedback Me</option>
							<option value="Other">Other</option>
						</select>
					</div>
				</div>
				<br/>
				<div class="item form-group">
					<div class="col-md-6 col-sm-6 col-xs-12">
						<select id="submodule" name="submodule" class="form-control">
							<option disabled selected value style="display:none"> -- select submodule -- </option>
						</select>
					</div>
				</div>
				<br/>
				<br/>
				<div class="item form-group">
					<div style="font-weight:bold;">What do you like about SRT and what can we improve on?</div>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<textarea id="suggestion" name="suggestion" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;"></textarea>
					</div>
				</div>
				<br/>
				<br clear="all" />
				<div class="item form-group">
					<div style="font-weight:bold;">Attachment  <a id="add-attachment"><i class="fa fa-plus-square"></i></a></div>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<ul class="feedback-attachment"><li><input type="file" name="feedback_image[]" style="display:inline-block"> <i class="fa fa-trash remove-attachment" style="cursor:pointer;" onclick="$(this).closest('li').remove();"></i></ul>
					</div>
				</div>
				<br/>
				<br clear="all" />
			<div class="ln_solid"></div>
			  <div class="form-group">
				<div class="col-md-12" style="text-align:center;">
				  <button id="send" type="submit" class="btn btn-success" style="width:250px;">Send</button>
				</div>
			  </div>
		  </div>
		  </form>
		</div>
	  </div>
	</div>
  </div>
</div>
<!-- /page content -->

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('#submodule').hide();

	$("#module_menu").change(function() {
		var module = $( this ).val();
		if(module === "Security" || module === "Safety" || module === "Parking & Traffic" || module === "Housekeeping" || module === "Operational Mall" || module === "Manager On Duty")
		{
			$('#submodule').show();
			$("#submodule").empty();
			<?php if($this->showSecurity || $this->showChiefSecurity || $this->showSafety || $this->showParkingTraffic || $this->showHousekeeping || $this->showOM || $this->showMod) { ?>$("#submodule").append(new Option("Daily Report", "Daily Report"));<?php } ?>
			if(module === "Security" || module === "Safety" || module === "Parking & Traffic" || module === "Housekeeping")
			{
				<?php if($this->showSecurityActionPlan || $this->showSafetyActionPlan || $this->showParkingActionPlan || $this->showHousekeepingActionPlan) { ?>$("#submodule").append(new Option("Action Plan", "Action Plan"));<?php } ?>
				if(module === "Security" || module === "Safety" || module === "Parking & Traffic")
				{
					<?php if($this->showSecurityMonthlyAnalysis || $this->showSafetyMonthlyAnalysis || $this->addParkingMonthlyAnalysis) { ?>$("#submodule").append(new Option("Monthly Analysis", "Monthly Analysis"));<?php } ?>
					<?php if($this->showSecurityPivotChart || $this->showSafetyPivotChart || $this->showParkingPivotChart) { ?>$("#submodule").append(new Option("Pivot Chart", "Pivot Chart"));<?php } ?>
					if(module === "safety")
					{
						<?php if($this->showSafetyBoard) { ?>$("#submodule").append(new Option("Safety Board", "Safety Board"));<?php } ?>
					}
				}
			}
			$("#submodule").append(new Option("Other", "Other"));
		}
		else
		{
			$('#submodule').hide();
			$("#submodule").empty();
		}
	});

	$('#feedback-form').on('submit', function(event){
		event.preventDefault(); 		
		$.ajax({
			url: '/default/feedback/send',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				alert("Thank you for your feedback, we really appreciate it!")
			}
		});
	});

	$('#add-attachment').on('click', function(event){
		$( ".feedback-attachment").append('<li><input type="file" name="topic_image[]" style="display:inline-block"> <i class="fa fa-trash remove-attendance" style="cursor:pointer;" onclick="$(this).closest(\'li\').remove();"></i></li>');
	});
});	
</script>