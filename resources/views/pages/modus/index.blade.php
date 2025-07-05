@extends('layouts.master')

@section('styles')

@endsection

@section('content')

<!-- PAGE-HEADER // Menampilkan nama department
                $('#department-name').text(data.department_name);-->
<div class="page-header">
    <h1 class="page-title" id="modus-name"></h1>

    <!--<button class="btn btn-primary mt-3" id="addAreaButton">Add Area</button>-->
     <div class="ms-auto"> <!-- Menempatkan tombol di sisi kanan -->
                                    <button class="btn btn-primary me-2" id="addModusButton">Add Sub Item</button>
                                    <button class="btn btn-danger" id="delete-selected">Delete Sub Item</button>
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
                                        <th>Sub Item Name</th>
                                        <th>Item Name</th>
                                        <th>Sort Order</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="modusTableBody">
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
<div class="modal fade" id="addModusModal" tabindex="-1" aria-labelledby="addModusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModusModalLabel">Add Modus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addModusForm">
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


                     <div class="mb-3">
                        <label for="incident_name" class="form-label">Item Name</label>
                       <select class="form-control" name="incident_name">-->
                            @foreach($incident as $area_)
                            <option value="{{ $area_->id}}">{{ $area_->incident_name}}</option>
                            @endforeach
                        </select>
                    </div>
                      <div class="mb-3">
                        <label for="modus_name" class="form-label">Sub Item Name</label>
                        <input type="text" class="form-control" id="modus_name" name="modus_name" required>
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
<div class="modal fade" id="editModusModal" tabindex="-1" aria-labelledby="editModusLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModusLabel">Edit Modus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editModusForm">
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
                        <label for="incident_id_edit" class="form-label">Item Name</label>
                        <input class="form-control" id="incident_id_edit" name="incident_id_edit" readonly>
                    </div>
                     <div class="mb-3">
                        <label for="area_name_edit" class="form-label">Sub Item Name</label>
                        <input type="text" class="form-control" id="modus_name_edit" name="modus_name_edit" required>
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
    document.getElementById('addModusButton').addEventListener('click', function() {
        new bootstrap.Modal(document.getElementById('addModusModal')).show();
    });

     const BASE_URL = "{{ url('/public/') }}";

     // Fungsi untuk mengecek apakah ada checkbox yang dipilih
    // function toggleDeleteButton() {
    //     const isAnyCheckboxChecked = $('.delete-checkbox:checked').length > 0;
    //     if (isAnyCheckboxChecked) {
    //         $('#delete-selected').show(); // Tampilkan tombol delete
    //     } else {
    //         $('#delete-selected').hide(); // Sembunyikan tombol delete
    //     }
    // }

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

    function fetchModus(departmentId) {
         if (!departmentId) {
        console.error('Incident ID is missing or undefined.');
        return;
    }

        $.ajax({
            url: `{{ url('/modus/data') }}/${departmentId}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let rows = '';
                data.moduss.forEach(modus => {
                    rows += `
                        <tr>
                            <td><input type="checkbox" class="delete-checkbox" data-id="${modus.id}"></td>
                            <td>${modus.modus_name}</td>
                            <td>${modus.incident_name}</td>
                            <td>${modus.sort_order}</td>
                            <td>
                                <!-- Tambahkan action seperti Edit/Delete -->
                                <button class="btn btn-sm btn-primary edit-modus-btn" data-id="${modus.id}"><i class="fe fe-edit"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('#modusTableBody').html(rows);

                $('#modus-name').text( data.department_name + ' Sub Item');

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

        fetchModus(departmentId);



    $('#addModusForm').on('submit', function(e) {
    e.preventDefault();

    // Ambil form data termasuk file
    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        url: '{{ route("modus.store") }}', // Pastikan ini mengarah ke route yang benar
        data: formData,
        processData: false, // Jangan proses data
        contentType: false, // Jangan set content-type secara manual
        success: function(response) {
            Swal.fire({
                title: 'Success!',
                text: response.success,
                icon: 'success',
                confirmButtonColor: '#907246'
            });
            $('#addModusModal').modal('hide');
            location.reload();
            fetchModus(); // Refresh data user
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error!',
                text: 'Error: ' + xhr.responseJSON.message,
                icon: 'error',
                confirmButtonColor: '#907246'
            });
        }
    });
});


$(document).on('click', '.edit-modus-btn', function() {
    var areaId = $(this).data('id');

    // Ambil data department melalui AJAX
    $.ajax({
        url: '/modus/' + areaId + '/edit', // Endpoint untuk mengambil data
        type: 'GET',
        success: function(response) {
            // Isi data ke dalam form
            $('#incident_id_edit').val(response.incident.incident_name);
             $('#modus_name_edit').val(response.modus_name);
            $('#site_id_edit').val(response.site ? response.site.site_name : '');
             $('#department_id_edit').val(response.department ? response.department.department_name : '');
             $('#sort_order_edit').val(response.sort_order);

            // Simpan ID ke form sebagai atribut data-id
            $('#editModusForm').data('id', response.id);

            // Tampilkan modal
            $('#editModusModal').modal('show');
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error!',
                text: 'Error: ' + xhr.responseJSON.message,
                icon: 'error',
                confirmButtonColor: '#907246'
            });
        }
    });
});

// Submit form untuk update
$('#editModusForm').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    var departmentId = $('#editModusForm').data('id'); // Ambil ID dari atribut data-id

    $.ajax({
        url: '/modus/' + departmentId, // Endpoint update
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
            $('#editModusModal').modal('hide');
            //fetchDepartments(); // Refresh data department
            location.reload(); // Auto-refresh halaman
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error!',
                text: 'Error: ' + xhr.responseJSON.message,
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
                url: '{{ route("modus.delete") }}', // Pastikan route delete ini sudah benar
                type: 'POST',
                data: {
                    ids: selectedIds,
                    _token: '{{ csrf_token() }}' // CSRF token untuk keamanan
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Selected items have been deleted successfully.',
                        icon: 'success',
                        confirmButtonColor: '#907246'
                    });
                    location.reload();
                    fetchIncident(); // Refresh data tabel setelah penghapusan
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


</script>



@endsection
