@extends('layouts.master')

@section('styles')

@endsection

@section('content')

<!-- PAGE-HEADER // Menampilkan nama department
                $('#department-name').text(data.department_name);-->
<div class="page-header">
    <h1 class="page-title" id="department-name"></h1>

   
     <div class="ms-auto"> <!-- Menempatkan tombol di sisi kanan -->
                                    <button class="btn btn-primary me-2" id="addLocationButton">Add Location</button>
                                    <button class="btn btn-danger" id="delete-selected">Delete Location</button>
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
                                        <th>Location Name</th>
                                        <th>Area Name</th>
                                        <th>Sort Order</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="LocationTableBody">
                                </tbody>
                            </table>
                            <button id="delete-selected" class="btn btn-danger btn-sm" style="display: none;">Delete Selected</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ROW-2 CLOSED -->

<!-- Modal Add Location -->
<div class="modal fade" id="addLocationModal" tabindex="-1" aria-labelledby="addLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLocationModalLabel">Add Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addLocationForm">
                    @csrf
                     <div class="mb-3">
                        <label for="site_id" class="form-label">Site</label>
                         <input type="hidden" class="form-control" id="site_id" name="site_id" value="{{ $filteredsites->site_id ?? '' }}">
                         <input class="form-control" value="{{ $filteredsites->site_name }}" readonly>
                      
                    </div>
                
                    <div class="mb-3">
                        <label for="department_id" class="form-label">Department</label>
                         <input type="hidden" class="form-control" id="department_id" name="department_id" value="{{ $department->id ?? '' }}">
                         <input class="form-control" value="{{ $department->department_name }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="Location_name" class="form-label">Location Name</label>
                        <input type="text" class="form-control" id="location_name" name="location_name" required>
                    </div>
                    
                     <div class="mb-3">
                        <label for="Area_name" class="form-label">Area Name</label>
                        <select class="form-control" name="area_name">
                            @foreach($area as $area_)
                            <option value="{{ $area_->id}}">{{ $area_->area_name}}</option>
                            @endforeach
                        </select>
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

<!-- Modal Edit Location -->
<div class="modal fade" id="editLocationModal" tabindex="-1" aria-labelledby="editLocationLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLocationLabel">Edit Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editLocationForm">
                @csrf
                @method('PUT') <!-- Method untuk edit -->
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="site_id_edit" class="form-label">Site</label>
                        <input class="form-control" id="site_id_edit" name="site_id_edit" value="{{ $filteredsites->site_name }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="department_id_edit" class="form-label">Department</label>
                        <input class="form-control" id="department_id_edit" name="department_id_edit" value="{{ $department_id }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="department_id_edit" class="form-label">Area</label>
                        <input class="form-control" id="area_id_edit" name="area_id_edit" readonly>
                    </div>
                       <div class="mb-3">
                        <label for="Location_name_edit" class="form-label">Location Name</label>
                        <input type="text" class="form-control" id="location_name_edit" name="location_name_edit" required>
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
    document.getElementById('addLocationButton').addEventListener('click', function() {
        new bootstrap.Modal(document.getElementById('addLocationModal')).show();
    });

    const BASE_URL = "{{ url('/public/') }}";
    
    // Event listener untuk checkbox individual
    $(document).on('change', '.delete-checkbox', function () {
        toggleDeleteButton();
    });
    
    // Event listener untuk checkbox "Select All"
    $('#select-all').on('change', function () {
        const isChecked = $(this).is(':checked');
        $('.delete-checkbox').prop('checked', isChecked);
        toggleDeleteButton();
    });
    
    // Ambil ID departemen dari URL
    const path = window.location.pathname;
    const parts = path.split('/');
    const departmentId = parts.pop(); // Ambil elemen terakhir (ID)
    
    // Debugging
    console.log('Full URL Path:', path);
    console.log('Department ID:', departmentId);
     
    // Fungsi untuk menampilkan data user di tabel
    function fetchLocation(departmentId) {
        if (!departmentId) {
            console.error('Department ID is missing or undefined.');
            return;
        }
        
        $.ajax({
            url: `{{ url('/location/data') }}/${departmentId}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let rows = '';
                data.locations.forEach(Location => {
                    rows += `
                        <tr>
                            <td><input type="checkbox" class="delete-checkbox" data-id="${Location.id}"></td>
                            <td>${Location.location_name}</td>
                            <td>${Location.area_name}</td>
                            <td>${Location.sort_order}</td>
                            <td>
                                <!-- Tambahkan action seperti Edit/Delete -->
                                <button class="btn btn-sm btn-primary edit-Location-btn" data-id="${Location.id}"><i class="fe fe-edit"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('#LocationTableBody').html(rows);
                
                $('#department-name').text('Locations ' + data.department_name);
        
                // Tambahkan event listener untuk select all checkbox
                $('#select-all').prop('checked', false);
                toggleDeleteButton();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching user data:', error);
            }
        });
    }

    // Panggil fungsi fetchUsers() saat halaman dimuat
    $(document).ready(function() {
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        fetchLocation(departmentId);
 
        $('#addLocationForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: '{{ route("location.store") }}',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.success,
                        icon: 'success',
                        confirmButtonColor: '#907246'
                    });
                    $('#addLocationModal').modal('hide');
                    location.reload();
                    fetchLocation(departmentId);
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON.message,
                        icon: 'error',
                        confirmButtonColor: '#907246'
                    });
                }
            });
        });

        $(document).on('click', '.edit-Location-btn', function() {
            var departmentId = $(this).data('id');
            
            $.ajax({
                url: '/location/' + departmentId + '/edit',
                type: 'GET',
                success: function(response) {
                    $('#location_name_edit').val(response.location_name);
                    $('#site_id_edit').val(response.site ? response.site.site_name : '');
                    $('#department_id_edit').val(response.department ? response.department.department_name : '');
                    $('#area_id_edit').val(response.area ? response.area.area_name : '');
                    $('#sort_order_edit').val(response.sort_order);
                    
                    $('#editLocationForm').data('id', response.id);
                    $('#editLocationModal').modal('show');
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON.message,
                        icon: 'error',
                        confirmButtonColor: '#907246'
                    });
                }
            });
        });

        // Submit form untuk update
        $('#editLocationForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var departmentId = $('#editLocationForm').data('id');

            $.ajax({
                url: '/location/' + departmentId,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.success,
                        icon: 'success',
                        confirmButtonColor: '#907246'
                    });
                    $('#editLocationModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON.message,
                        icon: 'error',
                        confirmButtonColor: '#907246'
                    });
                }
            });
        });

        $('#delete-selected').click(function () {
            const selectedIds = [];
            $('.delete-checkbox:checked').each(function () {
                selectedIds.push($(this).data('id'));
            });

            if (selectedIds.length === 0) {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Please select at least one item to delete.',
                    icon: 'warning',
                    confirmButtonColor: '#907246'
                });
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete the selected items?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#907246',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("location.delete") }}',
                        type: 'POST',
                        data: {
                            ids: selectedIds,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'Selected items have been deleted successfully.',
                                icon: 'success',
                                confirmButtonColor: '#907246'
                            });
                            fetchLocation(departmentId);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error deleting selected items:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred while deleting the selected items.',
                                icon: 'error',
                                confirmButtonColor: '#907246'
                            });
                        }
                    });
                }
            });
        });
    });
    
    // Fungsi untuk mengecek apakah ada checkbox yang dipilih
    function toggleDeleteButton() {
        const isAnyCheckboxChecked = $('.delete-checkbox:checked').length > 0;
        if (isAnyCheckboxChecked) {
            $('#delete-selected').show(); // Tampilkan tombol delete
        } else {
            $('#delete-selected').hide(); // Sembunyikan tombol delete
        }
    }
</script>



@endsection