<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<h2 class="pagetitle">Feedback Inbox</h2>
		  <div class="statistic-filter">
			<form id="statistic-filter-form" action="/default/feedback/inbox"  method="post">
				<div class="statistic-filter-field">Site : <select id="site_id" name="site_id">
							<option value="">All</option>
							<?php 
								if(!empty($this->sitesSelections))
								{
									foreach($this->sitesSelections as $site)
									{
							?>
								<option value="<?php echo $site['site_id']; ?>" <?php if($this->params['site_id']==$site['site_id']) echo "selected"; ?>><?php echo $site['initial']; ?></option>
							<?php
									}
								}
							?>
						</select>
				</div>

				<div class="statistic-filter-field">Module : <select id="module_menu" name="module_menu">
							<option value="">All</option>
							<option value="Issue Finding" <?php if($this->params['module_menu']=="Issue Finding") echo "selected"; ?>>Issue Finding</option>
							<option value="Security" <?php if($this->params['module_menu']=="Security") echo "selected"; ?>>Security</option>
							<option value="Safety" <?php if($this->params['module_menu']=="Safety") echo "selected"; ?>>Safety</option>
							<option value="Parking & Traffic" <?php if($this->params['module_menu']=="Parking & Traffic") echo "selected"; ?>>Parking & Traffic</option>
							<option value="Housekeeping" <?php if($this->params['module_menu']=="Housekeeping") echo "selected"; ?>>Housekeeping</option>
							<option value="Operational Mall" <?php if($this->params['module_menu']=="Operational Mall") echo "selected"; ?>>Operational Mall</option>
							<option value="Manager On Duty" <?php if($this->params['module_menu']=="Manager On Duty") echo "selected"; ?>>Manager On Duty</option>
							<option value="HOD Meeting" <?php if($this->params['module_menu']=="HOD Meeting") echo "selected"; ?>>HOD Meeting</option>
							<option value="IT Meeting" <?php if($this->params['module_menu']=="IT Meeting") echo "selected"; ?>>IT Meeting</option>
							<option value="Statistic" <?php if($this->params['module_menu']=="Statistic") echo "selected"; ?>>Statistic</option>
							<option value="Feedback Me" <?php if($this->params['module_menu']=="Feedback Me") echo "selected"; ?>>Feedback Me</option>
							<option value="Other" <?php if($this->params['module_menu']=="Other") echo "selected"; ?>>Other</option>
						</select>
				</div>

				<div class="statistic-filter-field">Submodule : <select id="submodule" name="submodule">
							<option value="">All</option>
							<option value="Daily Report" <?php if($this->params['submodule']=="Daily Report") echo "selected"; ?>>Daily Report</option>
							<option value="Action Plan" <?php if($this->params['submodule']=="Action Plan") echo "selected"; ?>>Action Plan</option>
							<option value="Monthly Analysis" <?php if($this->params['submodule']=="Monthly Analysis") echo "selected"; ?>>Monthly Analysis</option>
							<option value="Pivot Chart" <?php if($this->params['submodule']=="Pivot Chart") echo "selected"; ?>>Pivot Chart</option>
							<option value="Safety Board" <?php if($this->params['submodule']=="Safety Board") echo "selected"; ?>>Safety Board</option>
						</select>
				</div>
				<div class="statistic-filter-field"><input type="submit" id="view-site-stat" name="view-user-stat" value="Go" style="width:50px; margin-top:-1px;"></div>
			</form>
		</div>
		<br/>
		<div class="paging">
			<div class="record-indicator">Showing <?php echo $this->startRec." - ".$this->endRec." of ".$this->totalRec; ?> Feedback </div>
			<div class="paging-section">
				<?php if(!empty($this->firstPageUrl)) { ?><a href="<?php echo $this->firstPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-double-left" ></i></a><?php } ?>
				<?php if(!empty($this->prevUrl)) { ?><a href="<?php echo $this->prevUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-left" ></i></a><?php } ?>
				<span class="page-indicator" style="margin-right:10px; margin-left:10px;">Page <?php echo $this->curPage; ?> of <?php echo $this->totalPage; ?></span>
				<?php /*<a class="create-report"><img src="/images/report-icon.png" /></a>*/ ?>
				<?php if(!empty($this->nextUrl)) { ?><a href="<?php echo $this->nextUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-right" ></i></a><?php } ?>
				<?php if(!empty($this->lastPageUrl)) { ?><a href="<?php echo $this->lastPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-double-right"></i></a><?php } ?>
			</div>
		</div>
		  <table class="table">
			  <thead>
				<tr>
				  <th>From</th>
				  <th>Site</th>
				  <th>Module</th>
				  <th>Submodule</th>
				  <th>Date</th>
				</tr>
			  </thead>
			  <?php
				if(!empty($this->feedback))
				{
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->feedback as $fback) { 
				?>
				<tr <?php if(empty($fback['view'])) echo 'class="unread-feedback"'; ?> onclick="location.href='/default/feedback/viewdetail/id/<?php echo $fback['feedback_id']; ?>'" style="cursor:pointer;">
				  	<td class="date-column"><?php echo $fback['name']; ?></td>
				  	<td class="date-column"><?php echo $fback['initial']; ?></td>
					<td class="date-column"><?php echo $fback['module']; ?></td>
					<td class="date-column"><?php echo $fback['submodule']; ?></td>
					<td class="date-column"><?php echo $fback['date']; ?></td>
				</tr>
				<?php
						$i++;
					}
				?>				
			  </tbody>
			<?php
				}
			?>
			</table>
		<div class="paging">
			<div class="paging-section">
				<?php if(!empty($this->firstPageUrl)) { ?><a href="<?php echo $this->firstPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-double-left" ></i></a><?php } ?>
				<?php if(!empty($this->prevUrl)) { ?><a href="<?php echo $this->prevUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-left" ></i></a><?php } ?>
				<span class="page-indicator" style="margin-right:10px; margin-left:10px;">Page <?php echo $this->curPage; ?> of <?php echo $this->totalPage; ?></span>
				<?php /*<a class="create-report"><img src="/images/report-icon.png" /></a>*/ ?>
				<?php if(!empty($this->nextUrl)) { ?><a href="<?php echo $this->nextUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-right" ></i></a><?php } ?>
				<?php if(!empty($this->lastPageUrl)) { ?><a href="<?php echo $this->lastPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-double-right"></i></a><?php } ?>
			</div>
			<div class="record-indicator">Showing <?php echo $this->startRec." - ".$this->endRec." of ".$this->totalRec; ?> Feedback </div>
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
	
	
});
</script>