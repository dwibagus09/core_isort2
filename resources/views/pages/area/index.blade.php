@extends('layouts.master')

@section('styles')

@endsection

@section('content')

<!-- PAGE-HEADER // Menampilkan nama department
                $('#department-name').text(data.department_name);-->
<div class="page-header">
    <h1 class="page-title" id="area-name"></h1>

    <!--<button class="btn btn-primary mt-3" id="addAreaButton">Add Area</button>-->
     <div class="ms-auto"> <!-- Menempatkan tombol di sisi kanan -->
                                    <button class="btn btn-primary me-2" id="addAreaButton">Add Area</button>
                                    <button class="btn btn-danger" id="delete-selected">Delete Area</button>
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
                                        <th>Area Name</th>
                                        <th>Sort Order</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="areaTableBody">
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
<div class="modal fade" id="addAreaModal" tabindex="-1" aria-labelledby="addAreaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAreaModalLabel">Add Area</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addAreaForm">
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
                        <label for="area_name" class="form-label">Area Name</label>
                        <input type="text" class="form-control" id="area_name" name="area_name" required>
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
<div class="modal fade" id="editAreaModal" tabindex="-1" aria-labelledby="editAreaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAreaLabel">Edit Area</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAreaForm">
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
                        <label for="area_name_edit" class="form-label">Area Name</label>
                        <input type="text" class="form-control" id="area_name_edit" name="area_name_edit" required>
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
    document.getElementById('addAreaButton').addEventListener('click', function() {
        new bootstrap.Modal(document.getElementById('addAreaModal')).show();
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
   
    function fetchArea(departmentId) {
         if (!departmentId) {
        console.error('Department ID is missing or undefined.');
        return;
    }
        
        $.ajax({
            url: `{{ url('/area/data') }}/${departmentId}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let rows = '';
                data.areas.forEach(areas => {
                    rows += `
                        <tr>
                            <td><input type="checkbox" class="delete-checkbox" data-id="${areas.id}"></td>
                            <td>${areas.area_name}</td>
                           
                            <td>${areas.sort_order}</td>
                            <td>
                                <!-- Tambahkan action seperti Edit/Delete -->
                                <button class="btn btn-sm btn-primary edit-area-btn" data-id="${areas.id}"><i class="fe fe-edit"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('#areaTableBody').html(rows);
                
                $('#area-name').text( data.department_name + ' Areas');
        
                // Tambahkan event listener untuk select all checkbox
                $('#select-all').prop('checked', false);
                toggleDeleteButton();
                
            },
            error: function(xhr, status, error) {
                console.error('Error fetching user data:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch area data',
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
        
        fetchArea(departmentId);
 
    
   
    $('#addAreaForm').on('submit', function(e) {
    e.preventDefault();

    // Ambil form data termasuk file
    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        url: '{{ route("area.store") }}', // Pastikan ini mengarah ke route yang benar
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
            $('#addAreaModal').modal('hide');
            fetchArea(departmentId); // Refresh data area
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


$(document).on('click', '.edit-area-btn', function() {
    var areaId = $(this).data('id');
    
    // Ambil data department melalui AJAX
    $.ajax({
        url: '/area/' + areaId + '/edit', // Endpoint untuk mengambil data
        type: 'GET',
        success: function(response) {
            // Isi data ke dalam form
            $('#area_name_edit').val(response.area_name);
            $('#site_id_edit').val(response.site ? response.site.site_name : ''); 
             $('#department_id_edit').val(response.department ? response.department.department_name : '');
             $('#sort_order_edit').val(response.sort_order);
           
            // Simpan ID ke form sebagai atribut data-id
            $('#editAreaForm').data('id', response.id);

            // Tampilkan modal
            $('#editAreaModal').modal('show');
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON.message || 'Failed to fetch area data',
                confirmButtonColor: '#907246'
            });
        }
    });
});

// Submit form untuk update
$('#editAreaForm').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    var areaId = $('#editAreaForm').data('id'); // Ambil ID dari atribut data-id

    $.ajax({
        url: '/area/' + areaId, // Endpoint update
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
            $('#editAreaModal').modal('hide');
            fetchArea(departmentId); // Refresh data area
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
                url: '{{ route("area.delete") }}', // Pastikan route delete ini sudah benar
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
                    fetchArea(departmentId); // Refresh data tabel setelah penghapusan
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