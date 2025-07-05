@extends('layouts.master')

@section('styles')

@endsection

@section('content')

<!-- PAGE-HEADER // Menampilkan nama department
                $('#department-name').text(data.department_name);-->
<div class="page-header">
    <h1 class="page-title" id="incident-name"></h1>

    <!--<button class="btn btn-primary mt-3" id="addAreaButton">Add Area</button>-->
     <div class="ms-auto"> <!-- Menempatkan tombol di sisi kanan -->
                                    <button class="btn btn-primary me-2" id="addIncidentButton">Add Incident</button>
                                    <button class="btn btn-danger" id="delete-selected">Delete Incident</button>
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
                                        <th>Incident Name</th>
                                        <th>Sort Order</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="incidentTableBody">
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

<!-- Modal Add Area -->
<div class="modal fade" id="addIncidentModal" tabindex="-1" aria-labelledby="addIncidentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addIncidentModalLabel">Add Incident</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addIncidentForm">
                    @csrf
                      <div class="mb-3">
                        <label for="site_id" class="form-label">Site</label>
                        <input type="hidden" id="site_id" name="site_id" value="{{$filteredsites->site_id ?? ''}}">
                        <!-- Input Text untuk menampilkan Nama Department -->
                        <input type="text" class="form-control" id="site_name" value="{{ $filteredsites?->site_name ?? 'Tidak Diketahui' }}" readonly>
                       
                    </div>
                     <div class="mb-3">
                        <label for="department_id" class="form-label">Department</label>
                         <!-- Input Hidden untuk menyimpan ID -->
                        <input type="hidden" id="department_id" name="department_id" value="{{$department_id ?? ''}}">
                        <!-- Input Text untuk menampilkan Nama Department -->
                        <input type="text" class="form-control" id="department_name" value="{{ $department?->department_name ?? 'Tidak Diketahui' }}" readonly>
                    </div>
                 
                    <!-- <div class="mb-3">-->
                    <!--    <label for="Area_name" class="form-label">Area</label>-->
                    <!--    <select class="form-control" name="area_name">-->
                    <!--        @foreach($area as $area_)-->
                    <!--        <option value="{{ $area_->id}}">{{ $area_->area_name}}</option>-->
                    <!--        @endforeach-->
                    <!--    </select>-->
                    <!--</div>-->
                    
                    <!--<div class="mb-3">-->
                    <!--    <label for="Area_name" class="form-label">Location</label>-->
                    <!--    <select class="form-control" name="location_name">-->
                    <!--        @foreach($location as $area_)-->
                    <!--        <option value="{{ $area_->id}}">{{ $area_->location_name}}</option>-->
                    <!--        @endforeach-->
                    <!--    </select>-->
                    <!--</div>-->
        
                      <div class="mb-3">
                        <label for="area_name" class="form-label">Incident Name</label>
                        <input type="text" class="form-control" id="incident_name" name="incident_name" required>
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
<div class="modal fade" id="editIncidentModal" tabindex="-1" aria-labelledby="editIncidentLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAreaLabel">Edit Incident</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editIncidentForm">
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
                    <!--<div class="mb-3">-->
                    <!--    <label for="area_name_edit" class="form-label">Area</label>-->
                    <!--    <input type="text" class="form-control" id="area_name_edit" name="area_name_edit" required>-->
                    <!--</div>-->
                    <!-- <div class="mb-3">-->
                    <!--    <label for="area_name_edit" class="form-label">Location</label>-->
                    <!--    <input type="text" class="form-control" id="area_name_edit" name="location_name_edit" required>-->
                    <!--</div>-->
                     <div class="mb-3">
                        <label for="area_name_edit" class="form-label">Incident Name</label>
                        <input type="text" class="form-control" id="incident_name_edit" name="incident_name_edit" required>
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
    document.getElementById('addIncidentButton').addEventListener('click', function() {
        new bootstrap.Modal(document.getElementById('addIncidentModal')).show();
    });

     const BASE_URL = "{{ url('/public/') }}";
     
     // Fungsi untuk mengecek apakah ada checkbox yang dipilih
    function toggleDeleteButton() {
        const isAnyCheckboxChecked = $('.delete-checkbox:checked').length > 0;
        if (isAnyCheckboxChecked) {
            $('#delete-selected').show(); // Tampilkan tombol delete
        } else {
            $('#delete-selected').hide(); // Sembunyikan tombol delete
        }
    }
    
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
   
    function fetchIncident(departmentId) {
         if (!departmentId) {
        console.error('Incident ID is missing or undefined.');
        return;
    }
        
        $.ajax({
            url: `{{ url('/incident/data') }}/${departmentId}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let rows = '';
                data.incidents.forEach(incident => {
                    rows += `
                        <tr>
                            <td><input type="checkbox" class="delete-checkbox" data-id="${incident.id}"></td>
                            <td>${incident.incident_name}</td>
                           
                            <td>${incident.sort_order}</td>
                            <td>
                                <!-- Tambahkan action seperti Edit/Delete -->
                                <button class="btn btn-sm btn-primary edit-incident-btn" data-id="${incident.id}"><i class="fe fe-edit"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('#incidentTableBody').html(rows);
                
                $('#incident-name').text( data.department_name + ' Incident');
        
                // Tambahkan event listener untuk select all checkbox
                $('#select-all').prop('checked', false);
                toggleDeleteButton();
                
            },
            error: function(xhr, status, error) {
                console.error('Error fetching user data:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch incident data',
                    confirmButtonColor: '#907246'
                });
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
        
        fetchIncident(departmentId);
 
    
   
    $('#addIncidentForm').on('submit', function(e) {
    e.preventDefault();

    // Ambil form data termasuk file
    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        url: '{{ route("incident.store") }}', // Pastikan ini mengarah ke route yang benar
        data: formData,
        processData: false, // Jangan proses data
        contentType: false, // Jangan set content-type secara manual
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.success,
                confirmButtonColor: '#907246'
            });
            $('#addIncidentModal').modal('hide');
            location.reload();
            fetchIncident(); // Refresh data user
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON.message || 'An error occurred',
                confirmButtonColor: '#907246'
            });
        }
    });
});


$(document).on('click', '.edit-incident-btn', function() {
    var areaId = $(this).data('id');
    
    // Ambil data department melalui AJAX
    $.ajax({
        url: '/incident/' + areaId + '/edit', // Endpoint untuk mengambil data
        type: 'GET',
        success: function(response) {
            // Isi data ke dalam form
            $('#incident_name_edit').val(response.incident_name);
            $('#site_id_edit').val(response.site ? response.site.site_name : ''); 
             $('#department_id_edit').val(response.department ? response.department.department_name : '');
             $('#sort_order_edit').val(response.sort_order);
           
            // Simpan ID ke form sebagai atribut data-id
            $('#editIncidentForm').data('id', response.id);

            // Tampilkan modal
            $('#editIncidentModal').modal('show');
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON.message || 'Failed to load incident data',
                confirmButtonColor: '#907246'
            });
        }
    });
});

// Submit form untuk update
$('#editIncidentForm').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    var departmentId = $('#editIncidentForm').data('id'); // Ambil ID dari atribut data-id

    $.ajax({
        url: '/incident/' + departmentId, // Endpoint update
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.success,
                confirmButtonColor: '#907246'
            });
            $('#editIncidentModal').modal('hide');
            location.reload(); // Auto-refresh halaman
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON.message || 'Failed to update incident',
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
            icon: 'warning',
            title: 'Warning',
            text: 'Please select at least one item to delete.',
            confirmButtonColor: '#907246'
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
                url: '{{ route("incident.delete") }}', // Pastikan route delete ini sudah benar
                type: 'POST',
                data: {
                    ids: selectedIds,
                    _token: '{{ csrf_token() }}' // CSRF token untuk keamanan
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Selected items have been deleted successfully.',
                        confirmButtonColor: '#907246'
                    });
                    location.reload();
                    fetchIncident(); // Refresh data tabel setelah penghapusan
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting selected items:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while deleting the selected items.',
                        confirmButtonColor: '#907246'
                    });
                }
            });
        }
    });
});

    
});
    
    
</script>



@endsection