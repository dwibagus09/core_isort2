@extends('layouts.master')

@section('styles')

@endsection

@section('content')

<!-- PAGE-HEADER // Menampilkan nama department
                $('#department-name').text(data.department_name);-->
<div class="page-header">
    <h1 class="page-title" id="type-name"></h1>

    <!--<button class="btn btn-primary mt-3" id="addAreaButton">Add Area</button>-->
     <div class="ms-auto"> <!-- Menempatkan tombol di sisi kanan -->
                                    <button class="btn btn-primary me-2" id="addTypeButton">Add Type Kaizen</button>
                                    <button class="btn btn-danger" id="delete-selected">Delete Type Kaizen</button>
                                </div>
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
                                        <th><input type="checkbox" id="select-all"></th>
                                        <th>Type Name</th>
                                        <th>Sort Order</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="kaizenTypeTableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ROW-2 CLOSED -->

<!-- Modal Add Area -->
<div class="modal fade" id="addTypeModal" tabindex="-1" aria-labelledby="addTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTypeModalLabel">Add Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addTypeForm">
                    @csrf
                      <div class="mb-3">
                        <label for="site_id" class="form-label">Site</label>
                        <input type="hidden" class="form-control" id="site_id" name="site_id" value="{{$filteredsites->site_id}}" readonly>
                        <input type="text" class="form-control" value="{{$filteredsites->site_name}}" readonly>
                    </div>
                     <div class="mb-3">
                        <label for="department_id" class="form-label">Department</label>
                         <!-- Input Hidden untuk menyimpan ID -->
                        <input type="hidden" id="department_id" name="department_id" value="{{$department_id ?? ''}}">
                        <!-- Input Text untuk menampilkan Nama Department -->
                        <input type="text" class="form-control" id="department_name" value="{{ $department?->department_name ?? 'Tidak Diketahui' }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="area_name" class="form-label">Type Kaizen Name</label>
                        <input type="text" class="form-control" id="type_name" name="type_name" required>
                    </div>
                  
                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Area -->
<div class="modal fade" id="editTypeModal" tabindex="-1" aria-labelledby="editTypeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTypeLabel">Edit Area</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTypeForm">
                @csrf
                @method('PUT') <!-- Method untuk edit -->
                <div class="modal-body">
                         <div class="mb-3">
                        <label for="site_id_edit" class="form-label">Site</label>
                        <input type="text" class="form-control" id="site_id_edit" name="site_id_edit" readonly>
                    </div>
                 
                       <div class="mb-3">
                        <label for="department_id_edit" class="form-label">Department</label>
                        <input class="form-control" id="department_id_edit" name="department_id" value="{{ $department_id }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="area_name_edit" class="form-label">Type Kaizen Name</label>
                        <input type="text" class="form-control" id="type_name_edit" name="type_name_edit" required>
                    </div>
               
                    <div class="mb-3">
                        <label for="sort_order_edit" class="form-label">Sort Order</label>
                        <input type="number" class="form-control" id="sort_order_edit" name="sort_order_edit" required>
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
    // Tampilkan modal saat tombol Add User diklik
    document.getElementById('addTypeButton').addEventListener('click', function() {
        new bootstrap.Modal(document.getElementById('addTypeModal')).show();
    });

    const BASE_URL = "{{ url('/') }}";
     
    // Fungsi untuk mengecek apakah ada checkbox yang dipilih
    function toggleDeleteButton() {
        const isAnyCheckboxChecked = $('.delete-checkbox:checked').length > 0;
        $('#delete-selected').toggle(isAnyCheckboxChecked);
    }
    
    // Event listener untuk checkbox individual
    $(document).on('change', '.delete-checkbox', toggleDeleteButton);
    
    // Event listener untuk checkbox "Select All"
    $('#select-all').on('change', function() {
        $('.delete-checkbox').prop('checked', $(this).is(':checked'));
        toggleDeleteButton();
    });
    
    // Fungsi untuk mendapatkan departmentId dari URL dengan lebih robust
    function getDepartmentIdFromUrl() {
        const path = window.location.pathname;
        const parts = path.split('/').filter(part => part !== '');
        return parts[parts.length - 1]; // Ambil elemen terakhir
    }
    
    const departmentId = getDepartmentIdFromUrl();
    
    // Debugging
    console.log('Department ID from URL:', departmentId);
    
    // Validasi departmentId
    if (!departmentId || isNaN(departmentId)) {
        console.error('Invalid Department ID:', departmentId);
        Swal.fire('Error', 'Invalid Department ID', 'error');
    }

    // Fungsi untuk menampilkan data type kaizen di tabel
    function fetchType(deptId) {
        if (!deptId || deptId === 'undefined') {
            console.error('Department ID is invalid:', deptId);
            return;
        }
        
        $.ajax({
            url: `${BASE_URL}/typekaizen/data/${deptId}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (!data || !data.areas) {
                    console.error('Invalid response data:', data);
                    Swal.fire('Error', 'Invalid data received', 'error');
                    return;
                }
                
                let rows = '';
                data.areas.forEach(area => {
                    rows += `
                        <tr>
                            <td><input type="checkbox" class="delete-checkbox" data-id="${area.kaizen_type_id}"></td>
                            <td>${area.kaizen_type || 'N/A'}</td>
                            <td>${area.sort_order || 'N/A'}</td>
                            <td>
                                <button class="btn btn-sm btn-primary edit-type-btn" data-id="${area.kaizen_type_id}">
                                    <i class="fe fe-edit"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
                
                $('#kaizenTypeTableBody').html(rows);
                $('#type-name').text(`${data.department_name || 'Department'} Type Kaizen`);
                
                $('#select-all').prop('checked', false);
                toggleDeleteButton();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching type data:', error);
                Swal.fire('Error', 'Failed to load kaizen types', 'error');
            }
        });
    }

    // Panggil fungsi fetchType() saat halaman dimuat
    $(document).ready(function() {
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        fetchType(departmentId);
    
        $('#addTypeForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: '{{ route("typekaizen.store") }}',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.success) {
                        $('#addTypeModal').modal('hide');
                        $('#addTypeForm')[0].reset();
                        Swal.fire('Success!', response.message, 'success');
                        fetchType(departmentId); // Refresh data dengan departmentId yang benar
                    }
                },
                error: function(xhr) {
                    let errorMsg = xhr.responseJSON?.message || 'An error occurred';
                    Swal.fire('Error', errorMsg, 'error');
                }
            });
        });

        $(document).on('click', '.edit-type-btn', function() {
            var typeId = $(this).data('id');
            
            $.ajax({
                url: `${BASE_URL}/typekaizen/${typeId}/edit`,
                type: 'GET',
                success: function(response) {
                    if (!response) {
                        Swal.fire('Error', 'Invalid response data', 'error');
                        return;
                    }
                    
                    $('#type_name_edit').val(response.kaizen_type || '');
                    $('#site_id_edit').val(response.department.site.site_name);
                    $('#department_id_edit').val(response.department?.department_name || '');
                    $('#sort_order_edit').val(response.sort_order || '');
                   
                    $('#editTypeForm').data('id', response.kaizen_type_id);
                    $('#editTypeModal').modal('show');
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Failed to load data', 'error');
                }
            });
        });

        // Submit form untuk update
        $('#editTypeForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var typeId = $(this).data('id');

            $.ajax({
                url: `${BASE_URL}/typekaizen/${typeId}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-HTTP-Method-Override': 'PUT' // Untuk method override
                },
                success: function(response) {
                     $('#editTypeModal').modal('hide');
                    if (response.success) {
                        Swal.fire('Success', response.message, 'success');
                        // fetchType(departmentId);
                        location.reload();
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Update failed', 'error');
                }
            });
        });

        $('#delete-selected').click(function() {
            const selectedIds = $('.delete-checkbox:checked').map(function() {
                return $(this).data('id');
            }).get();

            if (selectedIds.length === 0) {
                Swal.fire('Info', 'Please select at least one item to delete.', 'info');
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("typekaizen.delete") }}',
                        type: 'POST',
                        data: {
                            ids: selectedIds,
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.message, 'success');
                                fetchType(departmentId);
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error', xhr.responseJSON?.message || 'Delete failed', 'error');
                        }
                    });
                }
            });
        });
    });
</script>



@endsection