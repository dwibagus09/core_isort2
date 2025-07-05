<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
			<h2 class="pagetitle">Safety Committee Issues / Projects History</h2>

			<div class="filter">
				<form id="filter-form" action="/default/safetycomittee/history"  method="post">
					<div class="filter-field">Filter by Department: 
						<select id="category-select" name="category" style="width:120px; padding:3px;">
							<option value="0">All</option>
							<?php foreach($this->categories as $cat) { ?>
							<option value="<?php echo $cat['category_id']; ?>" <?php if($cat['category_id'] == $this->category_id) echo "selected"; ?>><?php echo $cat['category_name']; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="filter-field"> 
						Issue/Project Name : <input type="text" name="project_name" name="project_name" value="<?php echo $this->project_name; ?>">
					</div>
					<div class="filter-field"> <input type="submit" id="filter-issue" name="filter-issue" value="Go" style="width:40px;" class="form-btn"></div>
				</form>
			</div>

			<?php if($this->totalPage > 0) { ?>
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
			<?php } ?>
		<div class="table-auto-scroll">
		  <table class="table table-striped">
			  <thead>
				<tr>
					<th width="100" rowspan="2">Dept</th>
					<th rowspan="2">Projects / Issues</th>					  
					<th colspan="3">Date</th>
					<th rowspan="2">Follow Up</th>
					<th rowspan="2">Accident Review</th>
					<th rowspan="2">Recommendation</th>
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
				  	<td><?php echo $topic['category_name'].$this->pic[$topic['category_id']]; ?></td>
					<td><?php echo $topic['topic']; ?></td>
					<td align="center"><div id="target-date<?php echo $topic['topic_id']; ?>"><?php echo $topic['targetdate']; ?></div></td>
					<td align="center"><div id="start-date<?php echo $topic['topic_id']; ?>"><?php echo $topic['startdate']; ?></div></td>
					<td align="center"><?php echo $topic['finishdate']; ?></td>
					<td><?php echo $topic['follow_up']; ?></td>
					<td><?php echo $topic['accident_review']; ?></td>
					<td><?php echo $topic['recommendation']; ?></td>
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
		</div>
		
		<?php if($this->totalPage > 0) { ?>
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
		<?php } ?>
	  </div>
	</div>
</div>
<!-- /page content -->


<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>  
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	var id;
	console.log("test");
	$('.image-popup-vertical-fit').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
		}
	});
	
});
</script>