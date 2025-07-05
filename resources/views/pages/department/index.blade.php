
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Department</h1>
                                <!--<ol class="breadcrumb">-->
                                <!--    <li class="breadcrumb-item"><a href="javascript:void(0);">Users</a></li>-->
                                <!--    <li class="breadcrumb-item active" aria-current="page">List</li>-->
                                <!--</ol>-->
                               <div class="ms-auto"> <!-- Menempatkan tombol di sisi kanan -->
                                    <button class="btn btn-primary me-2" id="addDeptButton">Add Department</button>
                                    <button class="btn btn-danger" id="delete-selected">Delete Department</button>
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
                                                        <table class="table card-table table-vcenter text-nowrap  align-items-center">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th><input type="checkbox" id="select-all"></th>
                                                                    <th>Nama Department</th>
                                                                    <th>Site</th>
                                                                    <th>Telegram ID</th>
                                                                     <th>Whatsapp ID</th>
                                                                      <th>Icon Menu</th>
                                                                       <th>Icon Thumbnail</th>
                                                                    <th>Action</th>
                                                                    
                                                                </tr>
                                                            </thead>
                                                            <tbody id="deptTableBody">
                                                            </tbody>
                                                        </table>
                                                        <!--<button id="delete-selected" class="btn btn-danger btn-sm" style="display: none;">Delete Selected</button>-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ROW-2 CLOSED -->

                          <!-- Modal Add User -->
                        <div class="modal fade" id="addDeptModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addUserModalLabel">Add Department</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="addDeptForm" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="dept name" class="form-label">Department Name</label>
                                                <input type="text" class="form-control" id="name_dept" name="name_dept" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="site" class="form-label">Site</label>
                                               <select class="form-control" id="site" name="site[]" multiple required>
                                                    <option value="">Select Site</option>
                                                    @foreach ($filteredsites as $site)
                                                        <option value="{{ $site->site_id }}">{{ $site->site_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                             <div class="mb-3">
                                                <label for="tele channel" class="form-label">Telegram Channel ID</label>
                                                <input type="text" class="form-control" id="nama_chtel" name="nama_chtel" required>
                                            </div>
                                            <div class="mb-3">
                                               <label for="wa group" class="form-label">Whatsapp Group ID</label>
                                                <input type="text" class="form-control" id="nama_grwa" name="nama_grwa" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="icon menu" class="form-label">Icon Menu</label>
                                                <select class="form-control" id="icon_menu" name="icon_menu" required>
                                                    <option value="">Select an Icon</option>
                                                    <option value="fe fe-home" data-icon="fe fe-home">Home</option>
                                                    <option value="fe fe-user" data-icon="fe fe-user">User</option>
                                                    <option value="fe fe-envelope" data-icon="fe fe-envelope">Envelope</option>
                                                    <option value="fe fe-cog" data-icon="fe fe-cog">Settings</option>
                                                    <option value="fe fe-trash" data-icon="fe fe-trash">Trash</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="role" class="form-label">Icon Thumbnail</label>
                                               <input type="file" class="form-control" id="thumb" name="thumb" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        
                        
                        <!-- Modal Edit Department -->
                        <div class="modal fade" id="editDeptModal" tabindex="-1" aria-labelledby="editDeptLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDeptLabel">Edit Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDeptForm" enctype="multipart/form-data">
                @csrf
                @method('PUT') <!-- Method untuk edit -->
                
                <div class="modal-body">
                    <!-- Department Name -->
                    <div class="mb-3">
                        <label for="name_dept_edit" class="form-label">Department Name</label>
                       
                        <input type="text" class="form-control" id="name_dept_edit" name="name_dept" required>
                    </div>
                    
                    <!-- Site -->
                    <div class="mb-3">
                        <label for="site_edit" class="form-label">Site</label>
                        <select class="form-control" id="site_edit" name="site"  required>
                            <option value="">Select Site</option>
                            @foreach ($sites as $site)
                                <option value="{{ $site->site_id }}">{{ $site->site_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Telegram Channel -->
                    <div class="mb-3">
                        <label for="nama_chtel_edit" class="form-label">Telegram Channel ID</label>
                        <input type="text" class="form-control" id="nama_chtel_edit" name="nama_chtel" required>
                    </div>
                    
                    <!-- Whatsapp Group -->
                    <div class="mb-3">
                        <label for="nama_grwa_edit" class="form-label">Whatsapp Group ID</label>
                        <input type="text" class="form-control" id="nama_grwa_edit" name="nama_grwa" required>
                    </div>
                    
                    <!-- Icon Menu -->
                    <div class="mb-3">
                        <label for="icon_menu_edit" class="form-label">Icon Menu</label>
                        <select class="form-control" id="icon_menu_edit" name="icon_menu" required>
                            <option value="">Select an Icon</option>
                            <option value="fe fe-home" data-icon="fe fe-home">Home</option>
                            <option value="fe fe-user" data-icon="fe fe-user">User</option>
                            <option value="fe fe-envelope" data-icon="fe fe-envelope">Envelope</option>
                            <option value="fe fe-cog" data-icon="fe fe-cog">Settings</option>
                            <option value="fe fe-trash" data-icon="fe fe-trash">Trash</option>
                        </select>
                    </div>
                    
                    <!-- Icon Thumbnail -->
                    <div class="mb-3">
                        <label for="thumb_edit" class="form-label">Icon Thumbnail</label>
                        <input type="file" class="form-control" id="thumb_edit" name="thumb">
                        <small class="text-muted">Leave blank if you don't want to change the thumbnail.</small>
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
document.getElementById('addDeptButton').addEventListener('click', function() {
    new bootstrap.Modal(document.getElementById('addDeptModal')).show();
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

// Fungsi untuk menampilkan data user di tabel
function fetchDepart() {
    $.ajax({
        url: '{{ route("department.data") }}',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            let rows = '';
            data.forEach(department => {
                rows += `
                    <tr>
                        <td><input type="checkbox" class="delete-checkbox" data-id="${department.id}"></td>
                        <td>${department.department_name}</td>
                        <td>${department.site_name}</td>
                        <td>${department.telegram_channel_id}</td>
                         <td>${department.whatsapp_group_id}</td>
                         <td><i class="${department.icon_menu}"></i></td>
                         <td><img src="${BASE_URL}/${department.icon_thumbnail}" alt="Thumbnail" style="width: 50px; height: auto;"></td>
    
                         <td>
                            <!-- Tambahkan action seperti Edit/Delete -->
                            <button class="btn btn-sm btn-primary edit-department-btn" data-id="${department.id}"><i class="fe fe-edit"></i></button>
                        </td>
                    </tr>
                `;
            });
            $('#deptTableBody').html(rows);
            
            // Tambahkan event listener untuk select all checkbox
            $('#select-all').prop('checked', false);
            toggleDeleteButton();
            
        },
        error: function(xhr, status, error) {
            console.error('Error fetching user data:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to fetch department data',
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
    
    fetchDepart();

    $('#addDeptForm').on('submit', function(e) {
        e.preventDefault();

        // Ambil form data termasuk file
        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: '{{ route("department.store") }}',
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
                $('#addDeptModal').modal('hide');
               fetchDepart();
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

    $(document).on('click', '.edit-department-btn', function() {
        var departmentId = $(this).data('id');
        
        // Ambil data department melalui AJAX
        $.ajax({
            url: '/department/' + departmentId + '/edit',
            type: 'GET',
            success: function(response) {
                // Isi data ke dalam form
                $('#name_dept_edit').val(response.department_name);
                $('#site_edit').val(response.site_id);
                $('#nama_chtel_edit').val(response.telegram_channel_id);
                $('#nama_grwa_edit').val(response.whatsapp_group_id);
                $('#icon_menu_edit').val(response.icon_menu);
                
                // Simpan ID ke form sebagai atribut data-id
                $('#editDeptForm').data('id', response.id);

                // Tampilkan modal
                $('#editDeptModal').modal('show');
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON.message || 'Failed to load department data',
                    confirmButtonColor: '#907246'
                });
            }
        });
    });

    // Submit form untuk update
    $('#editDeptForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var departmentId = $('#editDeptForm').data('id');

        $.ajax({
            url: '/department/' + departmentId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
            $('#editDeptModal').modal('hide');
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: response.success,
        confirmButtonColor: '#907246'
    }).then(() => {
        // Pastikan modal masih ada di DOM
        if ($('#editDeptModal').length) {
            // Gunakan timeout kecil sebagai fallback
            setTimeout(() => {
                 location.reload(); // Auto-reload halaman
            }, 100);
        } else {
            console.warn("Modal element not found");
            fetchDepart();
        }
    });
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
                text: 'Please select department(s) to delete.',
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
                    url: '{{ route("department.delete") }}',
                    type: 'POST',
                    data: {
                        ids: selectedIds,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Selected items have been deleted successfully.',
                            confirmButtonColor: '#907246'
                        });
                        fetchDepart();
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
