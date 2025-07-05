			<?php if(!empty($this->msg)) echo '<div class="msg">'.$this->msg.'</div>'; ?>
			
  <div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	  <div class="dashboard">
	      <h1 class="page-title">Safety Committee</h1>
	      <?php if($this->showAddSafetyComittee == 1) { ?>
	       <div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/safetycomittee/add">
				<div class="submenu-icon">
				    <img src="/images/add_report.png" /><div class="submenu-icon-title">Add Safety Committee</div>
				</div>
				</a>
			</div>		
			<?php } ?>
	       <div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/safetycomittee/view">
				<div class="submenu-icon">
				    <img src="/images/spv_report.png" /><div class="submenu-icon-title">View Safety Committee</div>
				</div>
				</a>
			</div>
			<?php if($this->showHistorySafetyComittee == 1){ ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/safetycomittee/history">
				<div class="submenu-icon">
				    <img src="/images/history.png" /><div class="submenu-icon-title">Project History</div>
				</div>
				</a>
			</div>
			<?php } ?>
	  </div> 	
    </div>
</div>
<!-- /page content -->

<script type="text/javascript">
$(document).ready(function() {
	$("#safety-comittee-menu").addClass('active');
	$("#safety-comittee-menu .child_menu").show();
});	
</script>