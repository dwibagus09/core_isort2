			<?php if(!empty($this->msg)) echo '<div class="msg">'.$this->msg.'</div>'; ?>
			
  <div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	  <div class="dashboard">
	      <h1 class="page-title">Digital MOM</h1>
	      <?php if($this->showAddHOD == 1) { ?>
	       <div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/hod/add">
				<div class="submenu-icon">
				    <img src="/images/add_report.png" /><div class="submenu-icon-title">Add Digital MOM</div>
				</div>
				</a>
			</div>		
			<?php } ?>
	       <div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/hod/view">
				<div class="submenu-icon">
				    <img src="/images/spv_report.png" /><div class="submenu-icon-title">View Digital MOM</div>
				</div>
				</a>
			</div>
			<?php if($this->showHistoryHOD == 1){ ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/hod/history">
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
	$("#bod-meeting-menu").addClass('active');
	$("#bod-meeting-menu .child_menu").show();
});	
</script>