<!-- <div class="modal fade" id="country-selector">
<div class="modal-dialog modal-dialog-centered" role="document">
<div class="modal-content country-select-modal">
<div class="modal-header">
<h6 class="modal-title">Choose Country</h6><button aria-label="Close" class="btn-close"
data-bs-dismiss="modal" type="button"><span aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
<ul class="row p-3">
<li class="col-lg-6 mb-2">
<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block active">
<span class="country-selector"><img alt="" src="{{asset('build/assets/images/flags/10.jpg')}}"
class="me-3 language"></span>USA
</a>
</li>
<li class="col-lg-6 mb-2">
<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
<span class="country-selector"><img alt=""
src="{{asset('build/assets/images/flags/5.jpg')}}"
class="me-3 language"></span>Italy
</a>
</li>
<li class="col-lg-6 mb-2">
<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
<span class="country-selector"><img alt=""
src="{{asset('build/assets/images/flags/8.jpg')}}"
class="me-3 language"></span>Spain
</a>
</li>
<li class="col-lg-6 mb-2">
<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
<span class="country-selector"><img alt=""
src="{{asset('build/assets/images/flags/4.jpg')}}"
class="me-3 language"></span>India
</a>
</li>
<li class="col-lg-6 mb-2">
<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
<span class="country-selector"><img alt=""
src="{{asset('build/assets/images/flags/2.jpg')}}"
class="me-3 language"></span>French
</a>
</li>
<li class="col-lg-6 mb-2">
<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
<span class="country-selector"><img alt=""
src="{{asset('build/assets/images/flags/7.jpg')}}"
class="me-3 language"></span>Russia
</a>
</li>
<li class="col-lg-6 mb-2">
<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
<span class="country-selector"><img alt=""
src="{{asset('build/assets/images/flags/3.jpg')}}"
class="me-3 language"></span>Germany
</a>
</li>
<li class="col-lg-6 mb-2">
<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
<span class="country-selector"><img alt=""
src="{{asset('build/assets/images/flags/1.jpg')}}"
class="me-3 language"></span>Argentina
</a>
</li>
<li class="col-lg-6 mb-2">
<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
<span class="country-selector"><img alt="" src="{{asset('build/assets/images/flags/6.jpg')}}"
class="me-3 language"></span>Malaysia
</a>
</li>
<li class="col-lg-6 mb-2">
<a href="javascript:void(0);" class="btn btn-country btn-lg btn-block">
<span class="country-selector"><img alt="" src="{{asset('build/assets/images/flags/9.jpg')}}"
class="me-3 language"></span>Turkey
</a>
</li>
</ul>
</div>
</div>
</div>
</div> -->

<div class="modal fade" id="modal-submit-kaizens">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="font-weight:900 !important">Submit Kaizen</h4>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form-submit-kaizens">
				@csrf
				<input type="hidden" id="urlCurrent" name="urlCurrent" >
				<input type="file" id="fileInputKaizen" name="photo" style="display: none;" accept="image/*">
				<div class="modal-body">
					<!-- <img id="previewImageKaizen" src="" alt="Preview" style="max-width: 100%; height: auto; display: none;"> -->
					<div style="margin:5px;text-align:center">
						<canvas id="image-holder" style="border:1px solid grey;touch-action: none !important;max-width: 100%; "></canvas>
						<div style="margin-top:5px;text-align:left">
							<span>Size Line : </span>
							<input type="range" min="1" max="50" value="25" id="sizeRange" style="width:100%;margin-top:5px" />
						</div>
						<div style="margin-top:10px;text-align:left;" >
							<span style="text-align:left">Color Line : </span>
							<div class="d-flex flex-wrap gap-2 align-items-center">
								<div class="d-flex align-items-center">
									<input type="radio" name="colorRadio" value="black" checked style="margin-inline-end: 2px;" />
									<label for="black" style="margin-top:9px">Black</label>
								</div>
								<div class="d-flex align-items-center">
									<input type="radio" name="colorRadio" value="white" style="margin-inline-end: 2px;" />
									<label for="white" style="margin-top:9px">White</label>
								</div>
								<div class="d-flex align-items-center">
									<input type="radio" name="colorRadio" value="red" style="margin-inline-end: 2px;" />
									<label for="red" style="margin-top:9px">Red</label>
								</div>
								<div class="d-flex align-items-center">
									<input type="radio" name="colorRadio" value="green" style="margin-inline-end: 2px;" />
									<label for="green" style="margin-top:9px">Green</label>
								</div>
								<div class="d-flex align-items-center">
									<input type="radio" name="colorRadio" value="blue" style="margin-inline-end: 2px;" />
									<label for="blue" style="margin-top:9px">Blue</label>
								</div>
							</div>
						</div>
						<div style="margin-top:5px;text-align:right">
							<button id="clear" type="button" class="btn btn-danger btn-sm">Clear Edit</button>
							<!--<button id="change-image" type="button">Change Image</button>-->
						</div>
						<hr>
					</div>
					<b>Department</b> <small style="color:red">*</small>
					<select name="department" class="form-control department_frm_kaizen" required>
						<option value="">Choose the Department</option>
						@php
						$menus = \App\Http\Controllers\MenuController::getMenu();
						@endphp
						@if(count($menus) > 0)
						@foreach ($menus as $key => $value)
						<option value="{{  $value['id'] }}">{{ $value['menu_name'] }}</option>
						@endforeach
						@endif
					</select>
					<br>
					<div id="LoaderKaizen" style="text-align:center">
						<img src="{{asset('build/assets/images/svgs/loader.svg')}}">
					</div>
					<div id="loadBodyKaizen">
						<b>Area</b> <small style="color:red">*</small>
						<select name="area" class="form-control area_frm_kaizen" required>
						</select>
						<br>
						<b>Location</b> <small style="color:red">*</small>
						<select name="location" class="form-control location_frm_kaizen" required style="background-color:#f1f1f9">
						</select>
						<br>
						<b>Detail Location <small style="color:red">*</small></b>
						<textarea name="detail_location" class="form-control detail_location_frm_kaizen" required readonly></textarea>
						<br>
						<b>Type</b> <small style="color:red">*</small>
						<select name="type" class="form-control type_frm_kaizen" required>
						</select>
						<br>
						<b>Item</b> <small style="color:red">*</small>
						<select name="incident" class="form-control incident_frm_kaizen" required>
						</select>
						<br>
						<b>Sub Item</b> <small style="color:red">*</small>
						<select name="modus" class="form-control modus_frm_kaizen" required style="background-color:#f1f1f9">
						</select>
						<br>
						<b>Detail Item <small style="color:red">*</small></b>
						<textarea name="detail_modus" class="form-control detail_modus_frm_kaizen" required readonly></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<!-- <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button> -->
					<button type="submit" id="submit-kaizen-btn" class="btn btn-primary btn-block">Submit Kaizen</button>
				</div>
			</form>
		</div>
	</div>
</div>
