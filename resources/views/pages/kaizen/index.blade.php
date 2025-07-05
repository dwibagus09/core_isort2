@extends('layouts.master')

@section('styles')
<style>
.pagination {
  margin: 0;
}

.page-item.active .page-link {
  background-color: #907246;
  border-color: #907246;
}

.page-link {
  color: #907246;
  cursor: pointer;
}

.table th, .table td {
  vertical-align: middle;
}
.table th:nth-child(1), .table td:nth-child(1) {
  width: 5%;
}
.table th:nth-child(2), .table td:nth-child(2) {
  width: 10%;
}
.table th:nth-child(3), .table td:nth-child(3) {
  width: 15%;
}
.table th:nth-child(4), .table td:nth-child(4) {
  width: 50%;
}
.table th:nth-child(5), .table td:nth-child(5) {
  width: 20%;
}
.red {
  border: 2px solid red;
  border-radius: 5px;
}
.gold {
  border: 2px solid #907246;
  border-radius: 5px;
}
.greens {
  border: 2px solid green;
  border-radius: 5px;
}
.yellow {
  border: 2px solid yellow;
  border-radius: 5px;
}
.vertical-center {
  display: flex;
  flex-direction: column;
  justify-content: center;
  height: 100%;
}

/* Update CSS Department */
.icon-grid-kaizen {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
  gap: 2rem;
  justify-items: center;
  align-items: center;
  max-width: 800px;
  margin: 0 auto;
}

.icon-box-kaizen {
  cursor: pointer;
  background: #fff;
  border: 2px solid #ddd;
  border-radius: 12px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 120px;
  height: 100px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  transition: border-color 0.3s, color 0.3s;
}

.icon-box-kaizen i {
  font-size: 2rem;
  margin-bottom: 0.5rem;
  color: #ddd;
  transition: color 0.3s;
}

.icon-box-kaizen:hover {
  border-color: #907246;
}

.icon-box-kaizen:hover i {
  color: #907246;
}

.icon-box-kaizen.active {
  border-color: #907246;
}

.icon-box-kaizen.active i {
  color: #907246;
}

@media (max-width: 600px) {
  .icon-grid-kaizen {
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
  }
  .icon-box-kaizen {
    width: 90px;
    max-width: 90px;
  }
}

/* Update CSS Multiple image progress */
.image-preview {
  position: relative;
  margin: 5px;
}

.image-preview img {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.remove-btn {
  position: absolute;
  top: -5px;
  right: -5px;
  background: red;
  color: white;
  border: none;
  font-size: 12px;
  border-radius: 50%;
  cursor: pointer;
  width: 18px;
  height: 18px;
  line-height: 14px;
  text-align: center;
}
</style>
@endsection

@section('content')
<!-- PAGE-HEADER -->
<div class="page-header">
  <div>
    <h1 class="page-title">Kaizen</h1>
    <h5 style="color:#bfbfbf;padding-top:4px;margin-top:5px;font-size:13px">
      Site Choose : {{ $sitename->site_name ?? 'All Site' }}
    </h5>
  </div>
  <!-- <div class="ms-auto"> -->
  <small class="btn btn-danger" id="delete-selected">Delete Kaizen</small>
  <!-- </div> -->
</div>
<!-- PAGE-HEADER END -->
@if(!empty($sitename->site_name))
<div class="card mt-2 mb-5 pt-3 pb-5 pl-2 pr-2">
  <div class="container mb-4">
    <h1 class="page-title text-center">Departments</h1>
  </div>
  <div class="icon-grid-kaizen">
    <div class="icon-box-kaizen active filter_department" data-iddepart="all">
      <i class="fe fe-box"></i>
      <small style="font-size:10px"> All Departement </small>
      <small style="font-size:10px"> ({{ $totalallkaizens }}) </small>
    </div>
    <?php foreach ($departments as $key => $value): ?>
      <div class="icon-box-kaizen filter_department" data-iddepart="{{ $value->id }}">
        <i class="{{ $value->icon_menu }}"></i>
        <small style="font-size:10px">{{ $value->department_name }}</small>
        <small style="font-size:10px">({{ $value->kaizens_count }}) </small>
      </div>
    <?php endforeach; ?>
  </div>
</div>
@endif
<!-- ROW-2 -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <div class="input-group mb-3">
          <input type="text" id="kaizenSearch" class="form-control" placeholder="Search by ID Kaizen">
          <button class="btn btn-primary" type="button" id="searchButton">
            <i class="fe fe-search"></i>
          </button>
        </div>
      </div>

      <div class="grid-margin">
        <div class="table-responsive">
          <table id="kaizenTable" class="table card-table table-vcenter text-nowrap align-items-center">
            <thead class="thead-light">
              <tr>
                <th style="width:5%;"><input type="checkbox" id="select-all"></th>
                <!-- <th style="width:5%">ID</th> -->
                <!-- <th>ID</th> -->
                <!-- <th style="width:10%">Department</th> -->
                <th style="width:60%">Detail</th>
                <!-- <th>Status</th> -->
                <th style="width:35%" class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody id="kaizenTableBody">
            </tbody>
          </table>

          <div class="row mt-3">
            <div class="col-md-6">
              <div class="dataTables_info" id="pageInfo">
                Showing 0 to 0 of 0 entries
              </div>
            </div>
            <div class="col-md-6">
              <div class="float-end">
                <ul class="pagination" id="pagination">
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- ROW-2 CLOSED -->

<!-- Modal Add Comment Kaizen -->
<div class="modal fade" id="modal-comment-kaizen">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" style="font-weight:900 !important">Add Comment to Kaizen</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form-modal-comment-kaizen" enctype="multipart/form-data" method="post">
        @csrf
        <input type="hidden" id="kaizen_id_comment" name="kaizen_id" >
        <div class="modal-body">
          <label>Comment</label>
          <textarea name="comment" class="form-control" required></textarea>
          <div class="mt-2">
            <label>Add File <small>(opsional)</small></label>
            <input type="file" class="form-control" name="file">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary btn-block">Add Comment</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Kaizen -->
<div class="modal fade" id="editKaizenModal" tabindex="-1" aria-labelledby="editKaizenModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="editTempLabel">Edit Kaizen</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editKaizenForm">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Department</label>
            <input type="text" class="form-control" id="department_display" readonly>
            <input type="hidden" name="department_id" id="department_id">
          </div>
          <div class="mb-3">
            <label class="form-label">Area</label>
            <select class="form-control" name="area_id" id="areakaizen_edit"></select>
          </div>
          <div class="mb-3">
            <label class="form-label">Location</label>
            <select class="form-control" name="location" id="locationkaizen_edit"></select>
          </div>
          <div class="mb-3">
            <label class="form-label">Kaizen Type</label>
            <select class="form-control" name="kaizen_type_id" id="issuekaizen_edit"></select>
          </div>
          <div class="mb-3">
            <label class="form-label">Incident</label>
            <select class="form-control" name="incident_id" id="incidentkaizen_edit"></select>
          </div>
          <div class="mb-3">
            <label class="form-label">Modus</label>
            <select class="form-control" name="modus_id" id="moduskaizen_edit"></select>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" id="descriptionkaizen_edit" rows="3" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
const BASE_URL = "{{ url('public') }}";

// Global variables for pagination and filtering
let currentPage = 1;
const rowsPerPage = 10;
let originalKaizenData = [];
let filteredData = [];
let departmentData = 'all';

// Toggle delete button based on checkbox state
function toggleDeleteButton() {
  const isAnyCheckboxChecked = $('.delete-checkbox:checked').length > 0;
  $('#delete-selected').toggle(isAnyCheckboxChecked);
}

// Fetch kaizen data from server
function fetchKaizen() {
  $.ajax({
    url: '{{ route("kaizen.data") }}',
    type: 'GET',
    data: { department_id: departmentData },
    dataType: 'json',
    success: function(response) {
      originalKaizenData = response;
      filteredData = [...response];
      updatePagination();
    },
    error: function(xhr) {
      console.error('Error:', xhr.responseText);
      $('#kaizenTableBody').html(`
        <tr><td colspan="3" class="text-center">Failed to load data</td></tr>
        `);
      }
    });
  }

  // Render kaizen table with data
  function renderKaizenTable(data) {
    let rows = '';

    if (data.length === 0) {
      rows = `<tr><td colspan="3" class="text-center">No kaizen data found</td></tr>`;
    } else {
      data.forEach(kaizen => {
        const issueDate = new Date(kaizen.issue_date);
        const formattedDate = !isNaN(issueDate) ?
        issueDate.toLocaleDateString('id-ID', {
          day: '2-digit',
          month: 'short',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit',
          second: '2-digit'
        }) : 'Invalid date';

        let progressImagesHTML = '';
        if (kaizen.filename && kaizen.filename.length > 0) {
          progressImagesHTML = kaizen.filename.map(image => `
            <a href="${image.filename}"" target="_blank">
            <img src="${image.filename}"
            class="img-status yellow mb-1 "
            style="width:70px;height:80px;">
            </a>
            `).join('');
          }

          let commentsKaizens = '';
          if (kaizen.comments && kaizen.comments.length > 0) {
            commentsKaizens = kaizen.comments.map(result => `
              ${result.isclosed > 0 ? `<span style="padding:5px;font-size:7px;color:white;display:inline-block;margin:0 0  4px 0;background-color:green;border-radius:5px"><i class="fe fe-check-circle"></i> Closed Comment</span><br>` : ''}
              <b style="font-size:11px">${result.user}</b><br>
              <small style="color:#907246;font-size:9px">${result.comment_date}</small>
              <div style="margin:5px 0 0 0;padding: 0">
                <small style="font-size:10px;">${result.comment}</small>
              </div>
              ${result.filename ? `
                <a href="${result.filename}"" target="_blank">
                <b style="font-size:10px">-- Check File -- </b>
                </a>` : ''}
                <div class="mt-2 mb-2" style="border-top:1px dashed #ddd"></div>
                `).join('');

              }

              rows += `
              <tr>
              <td style="text-align:left">
              #${kaizen.kaizen_id}<br>
              <input type="checkbox" class="delete-checkbox" data-id="${kaizen.kaizen_id}">
              </td>

              <td style="vertical-align:top">
              <div class="d-flex flex-column" style="margin-top:0px">

              <div>
              <b style=";font-size: clamp(10.5px, 1.5vw, 12px);color:red;border-bottom:1px dashed red; width: 100%;
              margin-bottom:7px;padding-bottom:1px">Opened Kaizen</b>
              <div class="d-flex flex-wrap gap-0 gap-md-3 align-items-start mt-1 mb-2">
                <div>
                  ${kaizen.picture ? `
                  <a href="${kaizen.picture}" target="_blank">
                  <img src="${kaizen.picture}"
                  class="img-status red mb-2 mt-1"
                  style="width:70px;height:80px;">
                  </a>
                  ` : ''}
                </div>

                <div class="content-issue" style="word-wrap: break-word; white-space: normal; text-align: left;">
                  <small style="color:red; font-size: clamp(10px, 1.5vw, 10px);">(${formattedDate})</small>
                  <div style="margin: 0 0 10px 0; padding: 0; line-height: 0.5;">
                    <b style=" font-size: clamp(10px, 1.5vw, 10px);"> ${kaizen.siteName} - ${kaizen.department_name}</b>
                  </div>
                  <div class="d-flex align-items-start gap-2 mt-1">
                  <i class="fe fe-users mt-1" style="font-size: clamp(10.5px, 1.5vw, 12px); min-width:16px;"></i>
                  <span style="font-size: clamp(10.5px, 1.5vw, 12px);">${kaizen.user_name} | ${kaizen.user_ids}</span>
                  </div>
                  <div class="d-flex align-items-start gap-2">
                  <i class="fe fe-map-pin mt-1" style="font-size: clamp(10.5px, 1.5vw, 12px); min-width:16px;"></i>
                  <span style="font-size: clamp(10.5px, 1.5vw, 12px);">${kaizen.area} ${kaizen.location} ${kaizen.description}</span>
                  </div>
                  <div class="d-flex align-items-start gap-2">
                  <i class="fe fe-alert-triangle mt-1" style="color:red; font-size: clamp(10.5px, 1.5vw, 12px); min-width:16px;"></i>
                  <span style="font-size: clamp(10.5px, 1.5vw, 12px);">${kaizen.type} - ${kaizen.incident} - ${kaizen.modus}</span>
                  </div>
                  <div class="d-flex align-items-start gap-2">
                  <i class="fe fe-info mt-1" style="color:orange; font-size: clamp(10.5px, 1.5vw, 12px); min-width:16px;"></i>
                  <span style="font-size: clamp(10.5px, 1.5vw, 12px);">${kaizen.keterangan}</span>
                  </div>
                </div>

              </div>
              </div>

                ${progressImagesHTML ? `<div><b style=";font-size: clamp(10.5px, 1.5vw, 12px);color:#907246;border-bottom:1px dashed #907246; width: 100%;
                margin-bottom:7px;padding-bottom:1px">Progress Kaizen</b>
                <div class="d-flex gap-2 align-items-start mb-2 mt-2"
                `+progressImagesHTML+`</div></div>` : ''}

                ${kaizen.solved_picture ? `<div><b style=";font-size: clamp(10.5px, 1.5vw, 12px);color:green;border-bottom:1px dashed green; width: 100%;
                  margin-bottom:7px;padding-bottom:1px">Closed Kaizen</b>
                  <div class="mt-2">
                  <a href="${kaizen.solved_picture}" target="_blank">
                  <img src="${kaizen.solved_picture}"
                  class="img-status greens"
                  style="width:70px;height:80px;">
                  </a> </div></div>` : ''}

                  </div>
                  </td>

                  <td style="position: relative;padding-inline-start:5px;padding-inline-end:5px;vertical-align:top">
                  ${kaizen.sessionSite !== 'all' ? `<div style="position: absolute; top:10px; right: 10px;">
                  <button class="btn btn-sm btn-primary add-comment-btn"
                  data-kaizen_id_comment="${kaizen.kaizen_id}" title="Add Comment to Kaizen">
                  <i class="fe fe-message-square"></i>
                  </button>
                  </div>` : ''}
                  <div class="comment-kaizen" ${kaizen.sessionSite !== 'all' ? `style="margin-top:35px;width:100%"` : `style="width:100%"` }>
                  ${commentsKaizens ? `<small style="padding-bottom:5px;font-size:10px;"><b style="color:#907246">Comments : </b></small>
                  <div style="border:1px solid #907246;border-radius:10px;padding:5px;word-wrap: break-word; white-space: normal;width:100%;line-height:1.0;">
                  `+commentsKaizens+`</div>`: ''}
                  </div>
                  </td>
                  </tr>
                  `;
                });
              }

              $('#kaizenTableBody').html(rows);
              $('#select-all').prop('checked', false);
              toggleDeleteButton();
            }

            // Filter kaizen data based on search term
            function filterKaizen(searchTerm) {
              if (!searchTerm) {
                filteredData = [...originalKaizenData];
              } else {
                filteredData = originalKaizenData.filter(kaizen => {
                  return (
                    String(kaizen.kaizen_id).includes(searchTerm) ||
                    kaizen.department_name.toLowerCase().includes(searchTerm) ||
                    kaizen.user_name.toLowerCase().includes(searchTerm) ||
                    kaizen.location.toLowerCase().includes(searchTerm) ||
                    kaizen.area.toLowerCase().includes(searchTerm) ||
                    kaizen.type.toLowerCase().includes(searchTerm) ||
                    kaizen.incident.toLowerCase().includes(searchTerm) ||
                    kaizen.modus.toLowerCase().includes(searchTerm) ||
                    kaizen.description.toLowerCase().includes(searchTerm) ||
                    kaizen.keterangan.toLowerCase().includes(searchTerm)
                  );
                });
              }

              currentPage = 1;
              updatePagination();
            }

            // Update pagination controls
            function updatePagination() {
              const totalPages = Math.ceil(filteredData.length / rowsPerPage);
              const start = (currentPage - 1) * rowsPerPage + 1;
              const end = Math.min(currentPage * rowsPerPage, filteredData.length);

              $('#pageInfo').text(`Showing ${start} to ${end} of ${filteredData.length} entries`);
              renderCurrentPage();

              let paginationHTML = '';
              const maxVisiblePages = 5;

              if (totalPages > 1) {
                // Previous button
                paginationHTML += `
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
                </li>`;

                // Page numbers
                let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

                if (endPage - startPage + 1 < maxVisiblePages) {
                  startPage = Math.max(1, endPage - maxVisiblePages + 1);
                }

                if (startPage > 1) {
                  paginationHTML += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
                  if (startPage > 2) {
                    paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                  }
                }

                for (let i = startPage; i <= endPage; i++) {
                  paginationHTML += `
                  <li class="page-item ${i === currentPage ? 'active' : ''}">
                  <a class="page-link" href="#" data-page="${i}">${i}</a>
                  </li>`;
                }

                if (endPage < totalPages) {
                  if (endPage < totalPages - 1) {
                    paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                  }
                  paginationHTML += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`;
                }

                // Next button
                paginationHTML += `
                <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
                </li>`;
              }

              $('#pagination').html(paginationHTML);
            }

            // Render current page data
            function renderCurrentPage() {
              const start = (currentPage - 1) * rowsPerPage;
              const end = start + rowsPerPage;
              const pageData = filteredData.slice(start, end);
              renderKaizenTable(pageData);
            }

            // Initialize the page
            $(document).ready(function() {
              $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
              });

              // Initial data load
              fetchKaizen();
              //Add Comment Click Event
              $(document).on('click', '.add-comment-btn', function(e) {
                e.preventDefault();
                $('#form-modal-comment-kaizen')[0].reset();

                let kaizen_id_comment = $(this).data('kaizen_id_comment');
                $('#kaizen_id_comment').val(kaizen_id_comment);
                $('#modal-comment-kaizen').modal('show');
              });

              $('#form-modal-comment-kaizen').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                  url: `/kaizen/addcomment`,
                  type: 'POST',
                  data: formData,
                  processData: false,
                  contentType: false,
                  success: function(response) {
                    if (response.success) {
                      $('#modal-comment-kaizen').modal('hide');
                      Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Comment added successfully',
                        timer: 2000,
                        showConfirmButton: false
                      }).then(() => {
                        fetchKaizen();
                      });
                    }
                  },
                  error: function(xhr) {
                    // console.error('Error adding comment:', xhr);
                    $('#modal-comment-kaizen').modal('hide');
                    Swal.fire({
                      icon: 'error',
                      title: 'Error',
                      text: 'Failed to add comment',
                      timer: 3000
                    });
                  }
                });
              });

              // Filter Department Click Event
              $(document).on('click', '.filter_department', function(e) {
                let idDepart = $(this).data('iddepart');
                departmentData = idDepart

                $('.filter_department').removeClass('active');
                $(this).addClass('active');

                $('#kaizenTableBody').html(`<tr><td colspan="3" class="text-center"> <img src="{{asset('build/assets/images/svgs/loader.svg')}}" width="50px"><br> -- Load Data Kaizens -- </td></tr>`);

                fetchKaizen();
              });

              // Checkbox events
              $(document).on('change', '.delete-checkbox', toggleDeleteButton);
              $('#select-all').on('change', function() {
                $('.delete-checkbox').prop('checked', $(this).is(':checked'));
                toggleDeleteButton();
              });

              // Pagination click event
              $(document).on('click', '.page-link', function(e) {
                e.preventDefault();
                const newPage = parseInt($(this).data('page'));

                if (!isNaN(newPage) && newPage !== currentPage) {
                  currentPage = newPage;
                  updatePagination();
                }
              });

              // Search functionality with debounce
              let searchTimer;
              $('#kaizenSearch').on('keyup', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                  filterKaizen($(this).val().toLowerCase());
                }, 100);
              });

              $('#searchButton').on('click', function() {
                filterKaizen($('#kaizenSearch').val().toLowerCase());
              });

              // Delete selected kaizens
              $('#delete-selected').click(function() {
                const selectedIds = [];
                $('.delete-checkbox:checked').each(function() {
                  selectedIds.push($(this).data('id'));
                });

                if (selectedIds.length === 0) {
                  Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one kaizen to delete',
                    timer: 2000
                  });
                  return;
                }

                Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#907246',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                  if (result.isConfirmed) {
                    $.ajax({
                      url: '{{ route("kaizen.delete") }}',
                      type: 'POST',
                      data: {
                        ids: selectedIds,
                        _token: '{{ csrf_token() }}'
                      },
                      success: function(response) {
                        Swal.fire({
                          icon: 'success',
                          title: 'Deleted!',
                          text: response.message || 'Selected kaizens deleted successfully',
                          timer: 2000,
                          showConfirmButton: false
                        }).then(() => {
                          fetchKaizen();
                        });
                      },
                      error: function(xhr) {
                        Swal.fire({
                          icon: 'error',
                          title: 'Error',
                          text: 'Error deleting kaizens: ' + (xhr.responseJSON?.message || 'Server error'),
                          timer: 3000
                        });
                      }
                    });
                  }
                });
              });

              $(document).on('click', '.edit-kaizen-btn', function() {
                const kaizenId = $(this).data('id');

                $.ajax({
                  url: `/admin/kaizen/${kaizenId}/edit`,
                  type: 'GET',
                  success: function(response) {
                    $('#editKaizenForm').data('kaizen-id', kaizenId);

                    // Set current values
                    $('#department_display').val(response.current_data.department.name);
                    $('#department_id').val(response.current_data.department.id);
                    $('#descriptionkaizen_edit').val(response.current_data.description || '');

                    // Initialize dropdowns with current selections
                    initDropdown('#areakaizen_edit', response.areas, response.current_data.area_id);
                    initDropdown('#locationkaizen_edit', response.locations, response.current_data.location_id);
                    initDropdown('#issuekaizen_edit', response.kaizen_types, response.current_data.kaizen_type_id);
                    initDropdown('#incidentkaizen_edit', response.incidents, response.current_data.incident_id);

                    // Initialize modus dropdown only if there are moduses
                    if (response.moduses && response.moduses.length > 0) {
                      initDropdown('#moduskaizen_edit', response.moduses, response.current_data.modus_id);
                    } else {
                      $('#moduskaizen_edit').empty().append('<option value="">No modus available</option>');
                    }

                    // Handle incident change to load moduses
                    $('#incidentkaizen_edit').off('change').on('change', function() {
                      const incidentId = $(this).val();
                      const modusDropdown = $('#moduskaizen_edit');

                      // Clear existing options
                      modusDropdown.empty();

                      if (incidentId) {
                        // Add loading state
                        modusDropdown.append('<option value="">Loading moduses...</option>');

                        $.ajax({
                          url: `/admin/get-moduses-by-incident/${incidentId}`,
                          type: 'GET',
                          success: function(response) {
                            // Clear loading message
                            modusDropdown.empty();

                            if (response.length > 0) {
                              // Add default option
                              modusDropdown.append('<option value="">Select Modus</option>');

                              // Add modus options
                              $.each(response, function(index, modus) {
                                modusDropdown.append($('<option>', {
                                  value: modus.id,
                                  text: modus.name
                                }));
                              });
                            } else {
                              modusDropdown.append('<option value="">No modus available</option>');
                            }
                          },
                          error: function(xhr) {
                            console.error('Error loading moduses:', xhr);
                            modusDropdown.empty().append('<option value="">Error loading moduses</option>');
                          }
                        });
                      } else {
                        modusDropdown.append('<option value="">Select incident first</option>');
                      }
                    });

                    $('#editKaizenModal').modal('show');
                  },
                  error: function(xhr) {
                    console.error('Error:', xhr);
                    Swal.fire({
                      title: 'Error!',
                      text: 'Failed to load data',
                      icon: 'error',
                      confirmButtonColor: '#907246'
                    });
                  }
                });
              });

              // Helper function to initialize dropdown
              function initDropdown(selector, data, selectedValue) {
                const dropdown = $(selector);
                dropdown.empty();

                if (data && data.length > 0) {
                  dropdown.append('<option value="">Select an option</option>');

                  $.each(data, function(index, item) {
                    const option = $('<option>', {
                      value: item.id,
                      text: item.name
                    });

                    if (item.id == selectedValue) {
                      option.attr('selected', 'selected');
                    }

                    dropdown.append(option);
                  });
                } else {
                  dropdown.append('<option value="">No options available</option>');
                }
              }



              // Form submission handler
              $('#editKaizenForm').on('submit', function(e) {
                e.preventDefault();
                const kaizenId = $(this).data('kaizen-id');

                $.ajax({
                  url: `/admin/kaizen/${kaizenId}`,
                  type: 'POST',
                  data: $(this).serialize(),
                  success: function(response) {
                    if (response.success) {
                      Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Kaizen updated successfully',
                        timer: 2000,
                        showConfirmButton: false
                      }).then(() => {
                        $('#editKaizenModal').modal('hide');
                        fetchKaizen();
                      });
                    }
                  },
                  error: function(xhr) {
                    console.error('Error updating kaizen:', xhr);
                    Swal.fire({
                      icon: 'error',
                      title: 'Error',
                      text: 'Failed to update kaizen: ' + (xhr.responseJSON?.message || 'Unknown error'),
                      timer: 3000
                    });
                  }
                });
              });

            });
            </script>
            @endsection
