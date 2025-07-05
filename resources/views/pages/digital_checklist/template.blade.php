@extends('layouts.master')

@section('content')
       <!-- Konten Utama dengan Blur Effect jika belum pilih site -->
   
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Digital Checklist Template</h1>
             @if($selectedBranchId !== 'all')
            <div class="ms-auto">
                <a href="{{ route('categorydc.index') }}"><button class="btn btn-warning me-2" id="addcatTempdcButton" @if(empty(session('selected_site_id'))))disabled @endif>Category Template</button></a>
                <a href="{{ route('subcategorydc.index') }}"><button class="btn btn-warning me-2" id="addsubcatTempdcButton" @if(empty(session('selected_site_id'))))disabled @endif>Sub Category Template</button></a>
                <button class="btn btn-primary me-2" id="addTempdcButton" @if(empty(session('selected_site_id'))))disabled @endif>Add Template</button>
                <button class="btn btn-danger" id="delete-selected" @if(empty(session('selected_site_id'))))disabled @endif>Delete Template</button>
            </div>
            @endif
        </div>
        <!-- PAGE-HEADER END -->

        <!-- ROW-2 -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="">
                        <div class="grid-margin">
                            <div class="">
                                <div class="table-responsive">
                                    <table class="table card-table table-vcenter text-nowrap align-items-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th><input type="checkbox" id="select-all" @if(empty(session('selected_site_id'))))disabled @endif></th>
                                                <th>Department</th>
                                                <th>Template Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                         @if($selectedBranchId === 'all')
                                         <tbody>
                                            <tr>
                                                <td colspan="4" class="empty-state">
                                                    <i class="fe fe-alert-triangle"></i>
                                                    <h5>Data Not Accessible</h5>
                                                    <p>Please select a site to view the checklist template</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                        @else
                                        <tbody id="tempdcTableBody">
                                        </tbody>
                                        @endif
                                        
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ROW-2 CLOSED -->

        <!-- Modal Add Template -->
        <div class="modal fade" id="addtempdcModal" tabindex="-1" aria-labelledby="addtempdcModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTempdcModalLabel">Add Template Name</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addTempForm" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="dept name" class="form-label">Sites</label>
                                <select class="form-control" name="sites_template" id="sites_template" required>
                                    @php
                                        $selectedBranch = session('selected_branch', 'all');
                                    @endphp
                                    @if($selectedBranch !== 'all')
                                        @foreach($filteredsites->where('site_id', $selectedBranch) as $site)
                                            <option value="{{ $site->site_id }}" selected>{{ $site->site_name }}</option>
                                        @endforeach
                                    @else
                                        <option value="">Select Sites</option>
                                        @foreach($filteredsites as $site)
                                            <option value="{{ $site->site_id }}">{{ $site->site_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="dept name" class="form-label">Department</label>
                                <select class="form-control" name="name_dept_tempdc" id="name_dept_tempdc" required>
                                    <option value="">Select Department</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tele channel" class="form-label">Template Name</label>
                                <input type="text" class="form-control" id="nama_tempdc" name="nama_tempdc" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal Edit Template -->
        <div class="modal fade" id="editTempModal" tabindex="-1" aria-labelledby="editDeptLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTempLabel">Edit Template</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editTemplateForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="dept name" class="form-label">Department</label>
                                <select id="department_dropdown_edit" name="name_dept_edit" class="form-control" required>
                                    <option value="">Select Department</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tele channel" class="form-label">Template Name</label>
                                <input type="text" class="form-control" id="nama_tempdc_edit" name="nama_tempdc_edit" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
  
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Handle popup informasi pilih site
        @if(empty(session('selected_site_id')) && empty(session('selected_branch')))
             // Add site selection functionality
            $('#site-selection').change(function() {
                var siteId = $(this).val();
                if (siteId) {
                    $.ajax({
                        url: '{{ route("save-selected-branch") }}',
                        method: 'POST',
                        data: { 
                            selected_branch: siteId
                        },
                        success: function() {
                            location.reload();
                        },
                        error: function(xhr) {
                            if(xhr.status === 419) { // CSRF token mismatch
                                location.reload();
                            } else {
                                alert('Error selecting site. Please try again.');
                            }
                        }
                    });
                }
            });
            
            // Make the modal non-dismissable
            $('#siteInfoModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            
            // Style the modal to be more prominent
            $('#siteInfoModal .modal-content').css({
                'border': '2px solid #ffc107',
                'box-shadow': '0 0 20px rgba(255,193,7,0.5)'
            });
            
            // Style the select dropdown
            $('#site-selection').addClass('form-select-lg').css({
                'font-weight': '500',
                'border': '2px solid #0d6efd'
            });
        @else
            // If site is already selected, initialize page functions
            initializePageFunctions();
            
            // Add change site button functionality
            $('#change-site-btn').click(function() {
                $.ajax({
                    url: '/clear-selected-site',
                    method: 'POST',
                    success: function() {
                        location.reload();
                    }
                });
            });
        @endif
        
        // Fungsi utama setelah site dipilih
        function initializePageFunctions() {
            // Setup CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            //Get Dropdown Department
            // $('#sites_template').on('change', function() {
            //     var siteId = $(this).val();
            //     if (siteId) {
            //         $.ajax({
            //             url: '/departments/' + siteId,
            //             type: 'GET',
            //             dataType: 'json',
            //             success: function(data) {
            //                 $('#name_dept_tempdc').empty().append('<option value="">Select Department</option>');
            //                 $.each(data, function(key, value) {
            //                     $('#name_dept_tempdc').append('<option value="' + value.id + '">' + value.department_name + '</option>');
            //                 });
            //             },
            //             error: function(xhr, status, error) {
            //                 console.error('Error fetching departments:', error);
            //             }
            //         });
            //     } else {
            //         $('#name_dept_tempdc').empty().append('<option value="">Select Department</option>');
            //     }
            // });
            // Set site_id dari session saat modal dibuka
            $('#addtempdcModal').on('show.bs.modal', function() {
                var selectedSiteId = "{{ session('selected_branch') }}";
                $('#sites_template').val(selectedSiteId).trigger('change');
            });
            
            // Get Dropdown Department (tetap sama)
            $('#sites_template').on('change', function() {
                var siteId = $(this).val();
                if (siteId) {
                    $.ajax({
                        url: '/departments/' + siteId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#name_dept_tempdc').empty().append('<option value="">Select Department</option>');
                            $.each(data, function(key, value) {
                                $('#name_dept_tempdc').append('<option value="' + value.id + '">' + value.department_name + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching departments:', error);
                        }
                    });
                } else {
                    $('#name_dept_tempdc').empty().append('<option value="">Select Department</option>');
                }
            });

            // Tampilkan modal saat tombol Add Template diklik
            $('#addTempdcButton').click(function() {
                $('#addtempdcModal').modal('show');
            });

            // Fungsi untuk mengecek checkbox yang dipilih
            function toggleDeleteButton() {
                const isAnyCheckboxChecked = $('.delete-checkbox:checked').length > 0;
                $('#delete-selected').prop('disabled', !isAnyCheckboxChecked);
            }
            
            // Event listener untuk checkbox
            $(document).on('change', '.delete-checkbox', toggleDeleteButton);
            $('#select-all').on('change', function() {
                $('.delete-checkbox').prop('checked', $(this).is(':checked'));
                toggleDeleteButton();
            });

            // Fetch data template
            fetchTemplate();
            
            // Submit form add template
            $('#addTempForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                
                $.ajax({
                    type: 'POST',
                    url: '{{ route("templatedc.store") }}',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert(response.success);
                        $('#addtempdcModal').modal('hide');
                        $('#addTempForm')[0].reset();
                        fetchTemplate();
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseJSON.message);
                    }
                });
            });
            
            // Edit template
            $(document).on('click', '.edit-template-btn', function() {
                var templateId = $(this).data('id');
                
                $.ajax({
                    url: '/admin/digitalchecklist/template/' + templateId + '/edit',
                    type: 'GET',
                    success: function(response) {
                        // Kosongkan dropdown department terlebih dahulu
                        $('#department_dropdown_edit').empty().append('<option value="">Select Department</option>');
                        
                        // Isi dropdown department dengan data yang diterima
                        $.each(response.departments, function(index, department) {
                            $('#department_dropdown_edit').append($('<option>', {
                                value: department.id,
                                text: department.department_name
                            }));
                        });
                        
                        // Set nilai form
                        $('#nama_tempdc_edit').val(response.template.template_name);
                        $('#department_dropdown_edit').val(response.template.department_id);
                        $('#editTemplateForm').data('id', response.template.template_id);
                        
                        // Tampilkan modal
                        $('#editTempModal').modal('show');
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseJSON.message);
                    }
                });
            });
            
            // Submit form edit template
            $('#editTemplateForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var templateId = $(this).data('id');
                
                $.ajax({
                    url: '/admin/digitalchecklist/template/' + templateId,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert(response.success);
                        $('#editTempModal').modal('hide');
                        fetchTemplate();
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseJSON.message);
                    }
                });
            });
            
            // Delete selected templates
            $('#delete-selected').click(function() {
                const selectedIds = [];
                $('.delete-checkbox:checked').each(function() {
                    selectedIds.push($(this).data('id'));
                });

                if (selectedIds.length === 0) {
                    alert('Please select template(s) to delete.');
                    return;
                }

                if (!confirm('Are you sure you want to delete the selected templates?')) {
                    return;
                }

                $.ajax({
                    url: '{{ route("templatedc.delete") }}',
                    type: 'POST',
                    data: {
                        ids: selectedIds,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('Selected templates have been deleted successfully.');
                        fetchTemplate();
                    },
                    error: function(xhr) {
                        alert('An error occurred while deleting the selected templates.');
                    }
                });
            });
        }
        
        // Fungsi fetch data template
        function fetchTemplate() {
            $.ajax({
                url: '{{ route("templatedc.data") }}',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let rows = '';
                    if (data.length > 0) {
                        data.forEach(template => {
                            rows += `
                                <tr>
                                    <td><input type="checkbox" class="delete-checkbox" data-id="${template.template_id}"></td>
                                    <td>${template.department_name}</td>
                                    <td>${template.template_name}</td>
                                    <td>
                                        <form action="{{ route('templatedc.indexlist') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="template_id" value="${template.template_id}">
                                            <input type="hidden" name="site_id" value="${template.site_id}">
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fe fe-list"></i>
                                            </button>
                                        </form>
                                        <button class="btn btn-sm btn-primary edit-template-btn" data-id="${template.template_id}">
                                            <i class="fe fe-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        rows = `
                            <tr>
                                <td colspan="4" class="empty-state">
                                    <i class="fe fe-database"></i>
                                    <h5>Tidak Ada Data Template</h5>
                                    <p>Belum ada template checklist yang tersedia untuk site ini</p>
                                </td>
                            </tr>
                        `;
                    }
                    $('#tempdcTableBody').html(rows);
                },
                error: function(xhr) {
                    console.error('Error fetching template data:', xhr.responseText);
                }
            });
        }
    });
</script>
@endsection