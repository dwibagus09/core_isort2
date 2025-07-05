<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="cqc-table">
    <table class="table table-striped">
        <thead>
        <tr>
            <th width="250">Module</th>
            <th width="250">Target</th>
            <th width="250">Activity</th>
            <th width="250">Date</th>
            <th>Documents</th>
            <th width="250">Remarks</th>
            <th width="100">Action</th>
        </tr>
        </thead>
        <?php
        if(!empty($this->ap))
        {
    ?>
        <tbody>
        <?php
            $i = 1;
            foreach($this->ap as $a) { 
        ?>
        <tr>
            <td class="date-column"><?php echo $a['module_name']; ?></th>
            <td class="date-column"><?php echo $a['target_name']; ?></td>
            <td class="date-column"><?php echo $a['activity_name']; ?></td>
            <td class="date-column"><?php echo $a['date']; ?></td>
            <td>
            <?php if(!empty($a['documents'])) { ?>
                <ul style="list-style:none; padding:0px;">
                <?php $i = 0; foreach($a['documents'] as $doc) { 
					if(substr($doc['uploaded_date'],0,10) < "2023-02-23") $url =  str_replace("ts.","tstest.",$this->baseUrl);
					else $url = $this->baseUrl;
					
                    if($i > 0 && $doc['cqc']!=$a['documents'][$i-1]['cqc'])
                    { ?>
                        <li class="documents-cqc-separator">&nbsp;</li>
                <?php
                    }	
                ?>
                    <li><i class="fa fa-paperclip"></i> <a href="<?php echo $url.'/actionplan/'.strtolower(str_replace(" & ", "", $this->category['category_name'])).'/'.substr($doc['uploaded_date'],0,4)."/".$doc['filename']; ?>" target="_blank"><?php if(empty($doc['description'])) echo $doc['filename']; else echo $doc['description']; ?></a></li>
                <?php $i++; } ?>
                </ul>
            <?php } ?>
            </td>
            <td class="date-column" id="cqc-remarks-<?php echo $a['schedule_id']; ?>"><?php
            if(!empty($a['cqc'])) {
                foreach($a['cqc'] as $cqc) { 
					if(substr($cqc['uploaded_date'],0,10) < "2023-02-23") $url =  str_replace("ts.","tstest.",$this->baseUrl);
					else $url = $this->baseUrl;
				?>
                <div class="cqc">
                <?php echo $cqc['remarks']; ?>
                <?php if(!empty($cqc['attachment'])) { ?><br/><i class="fa fa-paperclip"></i> <a href="<?php echo $url.'/actionplan/cqc/'.strtolower(str_replace(" & ", "", $this->category['category_name'])).'/'.substr($cqc['uploaded_date'],0,4)."/".$cqc['attachment']; ?>" target="_blank"><?php echo $cqc['attachment']; ?></a><?php } ?>
                </div>						  
                <?php }
            }
            if($a['cqc_approved'] == "1") { ?>
            <div class="cqc-approved">Data Sesuai</div>
            <?php } ?></td>
            <td class="action-column">
            <?php if($a['cqc_approved'] != "1") { 
                if($this->allowApproveCQC) { 
            ?>
            <a class="approve" data-id="<?php echo $a['schedule_id']; ?>" style="cursor:pointer;"><i class="fa fa-thumbs-up" style="font-size:20px;" ></i></a>&nbsp;&nbsp;
            <a class="add-remark" href="#remark-form" data-id="<?php echo $a['schedule_id']; ?>" style="cursor:pointer;"><i class="fa fa-comments" style="font-size:20px;" ></i></a>&nbsp;&nbsp;
            <?php } } ?>
            <?php if($this->allowUploadCQC) { ?>
            <a class="upload" href="#upload-form" data-id="<?php echo $a['schedule_id']; ?>" style="cursor:pointer;"><i class="fa fa-upload" style="font-size:20px;" ></i></a>&nbsp;&nbsp;	
            <?php } ?>
            </td>
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

<!-- Add Remark form -->
  <form action="" id="remark-form" class="mfp-hide white-popup-block" ><br/>
	<input type="hidden" name="action_plan_schedule_id" id="action_plan_schedule_id" />
	<input type="hidden" name="category_id" id="category_id" value="<?php echo $this->category['category_id']; ?>" />
	<label for="name">Remarks</label><br/>
	<textarea rows="3" name="cqc_remarks" id="cqc_remarks" style="width:100%;"></textarea><br/><br/>
    <input type="file" name="cqc_attachment" id="cqc_attachment" class="attachment-uploader" style="margin:7px 0px;">
	<div style="text-align:center; width:100%;">
	<input type="submit" class="form-btn" id="remark-submit" name="remark-submit" value="Submit">
	</div>
  </form>

  <!-- Add Upload form -->
  <form action="" id="upload-form" class="mfp-hide white-popup-block" ><br/>
	<input type="hidden" name="action_plan_schedule_id" id="action_plan_schedule_id" />
	<input type="hidden" name="category_id" id="category_id" value="<?php echo $this->category['category_id']; ?>" />
	<label for="name">File Description</label><br/>
	<textarea rows="2" name="description" id="description" style="width:100%;"></textarea><br/><br/>
    <input type="file" name="filename" id="filename" class="attachment-uploader" style="margin:7px 0px;">
	<div style="text-align:center; width:100%;">
	<input type="submit" class="form-btn" id="upload-submit" name="upload-submit" value="Submit">
	</div>
  </form>

<script src="/js/jquery.magnific-popup.min.js"></script>  
<script type="text/javascript">
  $(document).ready(function() {
	var report_date;
	
	$('.add-remark').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#cqc_remarks',
		callbacks: {
			open: function() {
				
			},
			close: function() {	
				$('#remark-form')[0].reset();
			}
		}
	});
	
	$(".add-remark").click(function() {
		var id = this.dataset.id;
		$("#action_plan_schedule_id").val(id);
		$( "#form-title" ).html("Add Target");
	});

	$('#remark-form').on('submit', function(event){
		event.preventDefault(); 
		$("body").mLoading();
		$.ajax({
			url: '/default/actionplan/addCQC',
			type: 'POST',
			data: new FormData($('form')[0]),
			cache: false,
			contentType: false,
			processData: false,

			xhr: function () {
				var myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload) {
					myXhr.upload.addEventListener('progress', function (e) {
					if (e.lengthComputable) {
						$('progress').attr({
						value: e.loaded,
						max: e.total
						});
					}
					}, false);
				}
				return myXhr;
			},
			success: function(response) {
				/*location.href="/default/actionplan/viewcqc/c/<?php echo $this->category['category_id']; ?>";*/
				var resp = jQuery.parseJSON(response);
				$( "#cqc-remarks-"+ resp.action_plan_schedule_id).html(resp.remark);
				$("body").mLoading('hide');
				$.magnificPopup.close();
			}
		});
	});

	$(".approve").click(function() {
		$("body").mLoading();
		var id = this.dataset.id;
		$.ajax({
			url: "/default/actionplan/approvecqc",
			data: { id : id }
		}).done(function(response) {
			/*location.href="/default/actionplan/viewcqc/c/<?php echo $this->category['category_id']; ?>";*/
			$( "#cqc-remarks-"+ response).html('<div class="cqc-approved">Data Sesuai</div>');
			$("body").mLoading('hide');
		});	 
	});

	$('.upload').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#description',
		callbacks: {
			open: function() {
				
			},
			close: function() {	
				$('#upload-form')[0].reset();
			}
		}
	});
	
	$(".upload").click(function() {
		var id = this.dataset.id;
		$("#action_plan_schedule_id").val(id);
	});

	$('#upload-form').on('submit', function(event){
		event.preventDefault(); 
		$("body").mLoading();
		$.ajax({
			url: '/default/actionplan/uploadattachmentaftercqc',
			type: 'POST',
			data: new FormData($('form')[0]),
			cache: false,
			contentType: false,
			processData: false,

			xhr: function () {
				var myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload) {
					myXhr.upload.addEventListener('progress', function (e) {
					if (e.lengthComputable) {
						$('progress').attr({
						value: e.loaded,
						max: e.total
						});
					}
					}, false);
				}
				return myXhr;
			},
			success: function(response) {
				location.href="/default/actionplan/viewcqc/c/<?php echo $this->category['category_id']; ?>";
			}
		});
	});
	
});
</script>