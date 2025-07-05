
<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">
<link type="text/css" href="/css/jquery.ui.chatbox.css" rel="stylesheet" />

  <div class="">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Feedback Detail</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Site Name</label>
					<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
						<?php echo $this->feedback['site_fullname']; ?>
					</div>
				</div>
				<br/>
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">From</label>
					<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
						<?php echo $this->feedback['name']; ?>
					</div>
				</div>
				<br/>
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Date</label>
					<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
						<?php echo $this->feedback['date']; ?>
					</div>
				</div>
				<br/>
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor-inhouse">Module</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<?php echo $this->feedback['module']; ?>
					</div>
				</div>
				<?php if(!empty($this->feedback['submodule'])) { ?>
				<br/>
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor-inhouse">Submodule</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<?php echo $this->feedback['submodule']; ?>
					</div>
				</div>
				<?php } ?>
				<br/>
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor-inhouse">Suggestion</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<?php echo nl2br($this->feedback['suggestion']); ?>
					</div>
				</div>
				
				<?php if(!empty($this->feedback['attachment'])) { ?>
					<br/>
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor-inhouse">Attachment</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<?php	foreach($this->feedback['attachment'] as $attachment) { 
									$filename = explode("/",$attachment['filename']);
							?>
									<a href="<?php echo "/feedback/".$attachment['filename']; ?>" target="_blank"><i class="fa fa-paperclip"></i> <?php echo $filename[2]; ?></a><br/>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
				<br clear="all" />
				<div class="ln_solid"></div>
				<div class="form-group">
					<div class="col-md-12" style="text-align:center;">
						<button id="back" type="button" class="btn btn-success" style="width:250px;" onclick="location.href='/default/feedback/inbox'">Back to Feedback Inbox</button>
					</div>
				</div>
				<br/>
				<br/>
			</div>
		</div>
	  </div>
	</div>
  </div>
</div>
<!-- /page content -->

<div id="chat_div"></div>



<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>  
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/jquery.ui.chatbox.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	
});
</script>