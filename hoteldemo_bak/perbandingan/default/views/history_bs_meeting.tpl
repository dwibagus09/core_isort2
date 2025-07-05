<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
			<h2 class="pagetitle">BS Meeting Issues / Projects History</h2>

			<div class="filter">
				<form id="filter-form" action="/default/bs/history"  method="post">
					<div class="filter-field">Filter by Site: 
						<select id="site-select" name="site" style="width:120px; padding:3px;">
							<option value="0">All</option>
							<?php foreach($this->sites as $site) { ?>
							<option value="<?php echo $site['site_id']; ?>" <?php if($site['site_id'] == $this->site_id) echo "selected"; ?>><?php echo $site['initial']; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="filter-field"> 
						Issue/Project Name : <input type="text" name="project_name" name="project_name" value="<?php echo $this->project_name; ?>">
					</div>
					<div class="filter-field"> <input type="submit" id="filter-issue" name="filter-issue" value="Go" style="width:30px;"></div>
				</form>
			</div>

			<div class="paging">
				<div class="record-indicator">Showing <?php echo $this->startRec." - ".$this->endRec." of ".$this->totalRec; ?>  Issues/Projects </div>
				<div class="paging-section">
					<?php if(!empty($this->firstPageUrl)) { ?><a href="<?php echo $this->firstPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-double-left" ></i></a><?php } ?>
					<?php if(!empty($this->prevUrl)) { ?><a href="<?php echo $this->prevUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-left" ></i></a><?php } ?>
					<span class="page-indicator" style="margin-right:10px; margin-left:10px;">Page <?php echo $this->curPage; ?> of <?php echo $this->totalPage; ?></span>
					<?php /*<a class="create-report"><img src="/images/report-icon.png" /></a>*/ ?>
					<?php if(!empty($this->nextUrl)) { ?><a href="<?php echo $this->nextUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-right" ></i></a><?php } ?>
					<?php if(!empty($this->lastPageUrl)) { ?><a href="<?php echo $this->lastPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-double-right"></i></a><?php } ?>
				</div>
			</div>
		  <table class="table table-striped">
			  <thead>
				<tr>
					<th width="100" rowspan="2">Site</th>
					<th rowspan="2">Projects / Issues</th>					  
					<th colspan="3">Date</th>
					<th rowspan="2">Follow Up</th>
					</tr>
					<tr>
					<th width="90">Target</th>
					<th width="90">Start</th>
					<th width="90">Finish</th>						  
				</tr>
			  </thead>
			  <?php
				if(!empty($this->topics))
				{
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->topics as $topic) { 
				?>
				<tr>
				  	<td><?php echo $topic['initial'].$this->pic[$topic['site_id']]; ?></td>
					<td><?php echo $topic['topic']; ?></td>
					<td align="center"><div id="target-date<?php echo $topic['topic_id']; ?>"><?php echo $topic['targetdate']; ?></div></td>
					<td align="center"><div id="start-date<?php echo $topic['topic_id']; ?>"><?php echo $topic['startdate']; ?></div></td>
					<td align="center"><?php echo $topic['finishdate']; ?></td>
					<td><?php echo $topic['follow_up']; ?></td>
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
				<div class="record-indicator">Showing <?php echo $this->startRec." - ".$this->endRec." of ".$this->totalRec; ?> Issues/Projects </div>
			</div>
	  </div>
	</div>
</div>
<!-- /page content -->

<!-- comment form -->
  <form action="" id="comment-form" class="mfp-hide white-popup-block" >
	<input type="hidden" name="report_id" id="report_id" />
	<div id="comments-content"></div>
	<label for="name">Comment</label><br/>
	<textarea rows="4" cols="25" name="comment" id="comment"></textarea>
	<input type="file" name="attachment" id="attachment" class="attachment-uploader" style="margin:7px 0px;">
	<input type="submit" id="add-comment-submit" name="add-comment-submit" value="Submit">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>  
  
<script type="text/javascript">
$(document).ready(function() {
	var id;
	
});
</script>