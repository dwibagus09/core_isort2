<!-- BACK-TO-TOP -->
<!-- <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a> -->

<!-- JQUERY JS -->
<script src="{{asset('build/assets/plugins/jquery/jquery.min.js')}}"></script>

<!-- BOOTSTRAP JS -->
<script src="{{asset('build/assets/plugins/bootstrap/js/popper.min.js')}}"></script>
<script src="{{asset('build/assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

<!-- SIDE-MENU JS -->
<script src="{{asset('build/assets/plugins/sidemenu/sidemenu.js')}}"></script>

<!-- STICKY js -->
@vite('resources/assets/js/sticky.js')

<!-- SIDEBAR JS -->
<script src="{{asset('build/assets/plugins/sidebar/sidebar.js')}}"></script>

<!-- Perfect SCROLLBAR JS-->
<script src="{{asset('build/assets/plugins/p-scroll/perfect-scrollbar.js')}}"></script>
<!-- <script src="{{asset('build/assets/plugins/p-scroll/pscroll.js')}}"></script> -->
<!-- <script src="{{asset('build/assets/plugins/p-scroll/pscroll-1.js')}}"></script> -->
<!--<script src="https://cdn.jsdelivr.net/npm/compress-js@2.0.2/dist/compressjs.min.js"></script>-->


<script>
$(document).ready(function () {
  const $fileInput = $('#fileInputKaizen');
  const $previewModal = $('#modal-submit-kaizens');
  const $sizeElement = $("#sizeRange");
  let size = $sizeElement.val();
  const $colorElement = $("input[name='colorRadio']");
  let color = $colorElement.filter(":checked").val();
  const canvasElement = document.getElementById("image-holder");
  const context = canvasElement.getContext("2d");
  const $clearElement = $("#clear");

  $sizeElement.on("input", function () {
    size = $(this).val();
  });

  $colorElement.on("click", function () {
    color = $(this).val();
  });

  $("#change-image").on("click", function () {
    $fileInput.trigger('click');
  });

  $('.btn-submit-kaizen').on('click', function () {
    let urlCurrent = window.location.pathname;
    $("#urlCurrent").val(urlCurrent);
    let status_camera = $(this).data('status');
    $fileInput.val('');
    $fileInput.removeAttr('capture');

    if (status_camera == 1) {
      $fileInput.attr('capture', 'environment');
    }

    $fileInput.trigger('click');
  });

  $fileInput.on('change', async function () {
    const file = this.files[0];
    if (file && file.type.startsWith('image/')) {
      const image = new Image();
      image.src = await fileToDataUri(file);
      $(image).on("load", function () {
        $('#form-submit-kaizens')[0].reset();
        $previewModal.modal('show');
        $('#LoaderKaizen').css('display','none');
        $('#loadBodyKaizen').css('display','none');
        drawOnImage(image);
      });
    } else {
      alert('Harap pilih file gambar yang valid');
    }
  });

  function fileToDataUri(file) {
    return new Promise((resolve) => {
      const reader = new FileReader();
      reader.onload = () => resolve(reader.result);
      reader.readAsDataURL(file);
    });
  }

  function getCoordinates(event) {
    const rect = canvasElement.getBoundingClientRect();
    const scaleX = canvasElement.width / rect.width;
    const scaleY = canvasElement.height / rect.height;

    let x, y;

    if (event.touches) {
      const touch = event.touches[0];
      x = (touch.clientX - rect.left) * scaleX;
      y = (touch.clientY - rect.top) * scaleY;
    } else {
      x = (event.clientX - rect.left) * scaleX;
      y = (event.clientY - rect.top) * scaleY;
    }

    return { x, y };
  }

  function drawOnImage(image = null) {
    if (image) {
      const imageWidth = image.width;
      const imageHeight = image.height;

      canvasElement.width = imageWidth;
      canvasElement.height = imageHeight;

      context.drawImage(image, 0, 0, imageWidth, imageHeight);
    }

    let isDrawing = false;

    canvasElement.removeEventListener("mousedown", mousedownHandler);
    canvasElement.removeEventListener("mousemove", mousemoveHandler);
    canvasElement.removeEventListener("mouseup", mouseupHandler);
    canvasElement.removeEventListener("touchstart", touchstartHandler);
    canvasElement.removeEventListener("touchmove", touchmoveHandler);
    canvasElement.removeEventListener("touchend", touchendHandler);

    function mousedownHandler(e) {
      e.preventDefault();
      isDrawing = true;
      const { x, y } = getCoordinates(e);
      context.beginPath();
      context.lineWidth = size;
      context.strokeStyle = color;
      context.lineJoin = "round";
      context.lineCap = "round";
      context.moveTo(x, y);
    }

    function mousemoveHandler(e) {
      e.preventDefault();
      if (isDrawing) {
        const { x, y } = getCoordinates(e);
        context.lineTo(x, y);
        context.stroke();
      }
    }

    function mouseupHandler(e) {
      e.preventDefault();
      isDrawing = false;
      context.closePath();
    }

    canvasElement.addEventListener("mousedown", mousedownHandler);
    canvasElement.addEventListener("mousemove", mousemoveHandler);
    canvasElement.addEventListener("mouseup", mouseupHandler);

    function touchstartHandler(e) {
      e.preventDefault();
      const { x, y } = getCoordinates(e);
      isDrawing = true;
      context.beginPath();
      context.lineWidth = size;
      context.strokeStyle = color;
      context.lineJoin = "round";
      context.lineCap = "round";
      context.moveTo(x, y);
    }

    function touchmoveHandler(e) {
      if (isDrawing) {
        e.preventDefault();
        const { x, y } = getCoordinates(e);
        context.lineTo(x, y);
        context.stroke();
      }
    }

    function touchendHandler(e) {
      e.preventDefault();
      isDrawing = false;
      context.closePath();
    }

    canvasElement.addEventListener("touchstart", touchstartHandler, { passive: false });
    canvasElement.addEventListener("touchmove", touchmoveHandler, { passive: false });
    canvasElement.addEventListener("touchend", touchendHandler, { passive: false });

    $clearElement.on("click", function () {
      if (image) {
        const imageWidth = image.width;
        const imageHeight = image.height;

        canvasElement.width = imageWidth;
        canvasElement.height = imageHeight;

        context.drawImage(image, 0, 0, imageWidth, imageHeight);
      }
    });

  }

  function compressCanvasFixed(canvas, callback) {
   canvas.toBlob(function (blob) {
     callback(blob);
   }, 'image/jpeg', 0.8);
  }

   $('#form-submit-kaizens').on('submit', function (e) {
    e.preventDefault();
    const $btn = $('#submit-kaizen-btn');
    $btn.prop('disabled', true).html('Loading Submit...');

    const canvas = document.getElementById('image-holder');

    compressCanvasFixed(canvas, function (compressedBlob) {
      const formData = new FormData();
      formData.append('_token', $('input[name="_token"]').val());
      formData.append('photo', compressedBlob, 'kaizen_photo.jpg');

      formData.append('department', $('[name="department"]').val());
      formData.append('area', $('[name="area"]').val());
      formData.append('location', $('[name="location"]').val());
      formData.append('detail_location', $('[name="detail_location"]').val());
      formData.append('type', $('[name="type"]').val());
      formData.append('incident', $('[name="incident"]').val());
      formData.append('modus', $('[name="modus"]').val());
      formData.append('detail_modus', $('[name="detail_modus"]').val());
      formData.append('urlCurrent', $('[name="urlCurrent"]').val());

      $.ajax({
        url: '/admin/kaizen/submit',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
          $btn.prop('disabled', false).html('Submit');
          $previewModal.modal('hide');
          if(res.status == 200){
            Swal.fire({
                title: 'Success!',
                text: res.message,
                icon: 'success',
                confirmButtonColor: '#907246'
            })
          }else{
            Swal.fire({
                  title: 'Error!',
                  text: res.message,
                  icon: 'error',
                  confirmButtonColor: '#907246'
              });
          }
        },
        error: function (xhr) {
          let message = 'An error occurred';
           if (xhr.responseJSON && xhr.responseJSON.message) {
             message = xhr.responseJSON.message;
           }
           $btn.prop('disabled', false).html('Submit');
           $previewModal.modal('hide');
           Swal.fire({
               title: 'Error!',
               text: message,
               icon: 'error',
               confirmButtonColor: '#907246'
           });
        }
      });
    });
   });


  $('.department_frm_kaizen').on('change',  function () {
    $('#loadBodyKaizen').css('display','none');
    $('#LoaderKaizen').css('display','block');
    let val = $(this).val();
    if(val == ""){
      $('#LoaderKaizen').css('display','none');
      $('#loadBodyKaizen .location_frm_kaizen, .modus_frm_kaizen').html('').css('background-color','#f1f1f9');
      $('#loadBodyKaizen textarea').val('').prop('readonly', true);
      alert('Please select the right option');
    }else{
      $.ajax({
        url: `/admin/kaizen/getdatatypeandarea/${val}`,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          $('#LoaderKaizen').css('display','none');
          $('#loadBodyKaizen').css('display','block');

          let areaOption = '<option value="">Choose Area</option>';
          data.area.forEach(areas => {
            areaOption += `<option value="${areas.id}">${areas.name}</option>`;
          });
          $('.area_frm_kaizen').html(areaOption);

          let typeOption = '<option value="">Choose Type</option>';
          data.type.forEach(types => {
            typeOption += `<option value="${types.id}">${types.name}</option>`;
          });
          $('.type_frm_kaizen').html(typeOption);

          let incidentOption = '<option value="">Choose Incident</option>';
          data.incident.forEach(incidents => {
            incidentOption += `<option value="${incidents.id}">${incidents.name}</option>`;
          });
          $('.incident_frm_kaizen').html(incidentOption);

        },
        error: function(xhr, status, error) {
          $('#LoaderKaizen').css('display','none');
          $('#loadBodyKaizen .location_frm_kaizen, .modus_frm_kaizen').html('').css('background-color','#f1f1f9');
          $('#loadBodyKaizen textarea').val('').prop('readonly', true);
          alert('Failed to load data, please try again..');
        }
      });
    }
  });

  $('.area_frm_kaizen').on('change',  function () {
    let val = $(this).val();
    if(val == ""){
      $('.location_frm_kaizen').html('').css('background-color','#f1f1f9');
      $('.detail_location_frm_kaizen').prop('readonly', true);
      alert('Please select the right option');
    }else{
      $.ajax({
        url: `/admin/get-locations/${val}`,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          let locationOption = '<option value="">Choose Location</option>';
          data.forEach(locations => {
            locationOption += `<option value="${locations.id}">${locations.location_name}</option>`;
          });
          if(data.length > 0){
            $('.location_frm_kaizen').html(locationOption).removeAttr('style');
            $('.detail_location_frm_kaizen').removeAttr('readonly');
          }else{
            $('.location_frm_kaizen').html('').css('background-color','#f1f1f9');
            $('.detail_location_frm_kaizen').prop('readonly', true);
          }
        },
        error: function(xhr, status, error) {
          $('.location_frm_kaizen').html('').css('background-color','#f1f1f9');
          $('.detail_location_frm_kaizen').prop('readonly', true);
          alert('Failed to load data, please try again..');
        }
      });
    }
  });

  $('.incident_frm_kaizen').on('change',  function () {
    let val = $(this).val();
    if(val == ""){
      $('.modus_frm_kaizen').html('').css('background-color','#f1f1f9');
      $('.detail_modus_frm_kaizen').prop('readonly', true);
      alert('Please select the right option');
    }else{
      $.ajax({
        url: `admin/get-moduses-by-incident/${val}`,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          let modusOption = '<option value="">Choose Modus</option>';
          data.forEach(modus => {
            modusOption += `<option value="${modus.id}">${modus.name}</option>`;
          });
          if(data.length > 0){
            $('.modus_frm_kaizen').html(modusOption).removeAttr('style');
            $('.detail_modus_frm_kaizen').removeAttr('readonly');
          }else{
            $('.modus_frm_kaizen').html('').css('background-color','#f1f1f9');
            $('.detail_modus_frm_kaizen').prop('readonly', true);
          }
        },
        error: function(xhr, status, error) {
          $('.modus_frm_kaizen').html('').css('background-color','#f1f1f9');
          $('.detail_modus_frm_kaizen').prop('readonly', true);
          alert('Failed to load data, please try again');
        }
      });
    }
  });

});
</script>



@yield('scripts')
