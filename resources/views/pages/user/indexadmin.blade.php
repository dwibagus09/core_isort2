
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Admin Users</h1>
                                <!--<ol class="breadcrumb">-->
                                <!--    <li class="breadcrumb-item"><a href="javascript:void(0);">Users</a></li>-->
                                <!--    <li class="breadcrumb-item active" aria-current="page">List</li>-->
                                <!--</ol>-->
                                <div class="ms-auto"> <!-- Menempatkan tombol di sisi kanan -->
                                <button class="btn btn-primary me-2" id="addUserButton"><i class="fe fe-plus"></i> User Admin</button>
                                <button class="btn btn-danger" id="delete-selected">Delete Admin</button>
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
                                                    <button id="delete-selected" class="btn btn-danger btn-sm " style="display: none;">Delete Selected</button>
                                                    <div class="table-responsive">
                                                        <table class="table card-table table-vcenter text-nowrap  align-items-center">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                     <th><input type="checkbox" id="select-all"></th>
                                                                    <th>Username/Email</th>
                                                                    <th>Name</th>
                                                                    <th>Role</th>
                                                                    <th>Action</th>
                                                                    
                                                                </tr>
                                                            </thead>
                                                            <tbody id="userTableBody">
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

                          <!-- Modal Add User -->
                        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="addUserForm">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username/Email</label>
                                                <input type="text" class="form-control" id="username" name="username" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Password</label>
                                                <input type="text" class="form-control" id="password" name="password" required>
                                            </div>
                                             <div class="mb-3">
                                                <label for="name" class="form-label">Confirm Password</label>
                                                <input type="text" class="form-control" id="confirm_password" name="confirm_password" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Nama</label>
                                                <input type="text" class="form-control" id="nama" name="nama" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="role" class="form-label">Role</label>
                                                <select class="form-control" id="role" name="role" required>
                                                    <option value="">Select Role</option>
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->role_id }}">{{ $role->role }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                          <div class="mb-3">
                                             

                                                <label for="site" class="form-label">Sites</label>
                                                <select class="form-control" id="site" name="site[]" multiple required>
                                                    @foreach($filteredSites as $site)
                                                        <option value="{{ $site->site_id }}">{{ $site->site_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        
                        <!-- Modal Edit User -->
                        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editUserForm">
                                             @csrf
                                             @method('PUT') <!-- Method untuk edit -->
                
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" class="form-control" id="username_edit" name="username">
                                            </div>
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Password</label>
                                                <input type="text" class="form-control" id="password_edit" name="password" >
                                            </div>
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Nama</label>
                                                <input type="text" class="form-control" id="nama_edit" name="nama" >
                                            </div>
                                            <div class="mb-3">
                                                <label for="role" class="form-label">Role</label>
                                                <select class="form-control" id="role_edit" name="role" >
                                                    <option value="">Select Role</option>
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->role_id }}">{{ $role->role }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> 

@endsection

@section('scripts')
<script>
    // Tampilkan modal saat tombol Add User diklik
    document.getElementById('addUserButton').addEventListener('click', function() {
        new bootstrap.Modal(document.getElementById('addUserModal')).show();
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
    
     // Fungsi untuk menampilkan data user di tabel
    function fetchUsers() {
        $.ajax({
            url: '{{ route("usersadmin.data") }}',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let rows = '';
                data.forEach(user => {
                    rows += `
                        <tr>
                            <td><input type="checkbox" class="delete-checkbox" data-id="${user.user_id}"></td>
                            <td>${user.username}</td>
                            <td>${user.name}</td>
                            <td>${user.role_name ? user.role_name : 'No Role'}</td>
                            <td>
                                 <!-- Tambahkan action seperti Edit/Delete -->
                                <button class="btn btn-sm btn-primary edit-user-btn" data-id="${user.user_id}"><i class="fe fe-edit"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('#userTableBody').html(rows);
                
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
        
        fetchUsers();
 
    
    // Fungsi untuk menambah user
    $('#addUserForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: '{{ route("usersadmin.store") }}', // Pastikan ini mengarah ke route yang benar
            data: $(this).serialize(),
            success: function(response) {
                alert(response.success);
                $('#addUserModal').modal('hide');
                fetchUsers(); // Refresh data user
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    });
    
     $(document).on('click', '.edit-user-btn', function() {
        var userId = $(this).data('id');
        
        // Ambil data department melalui AJAX
        $.ajax({
            url: '/users/' + userId + '/editadmin', // Endpoint untuk mengambil data
            type: 'GET',
            success: function(response) {
                // Isi data ke dalam form
                $('#username_edit').val(response.username);
                $('#password_edit').val(response.password);
                $('#nama_edit').val(response.name);
                $('#nope_edit').val(response.phone_no);
                $('#role_edit').val(response.role_id);
                
                // Simpan ID ke form sebagai atribut data-id
                $('#editUserForm').data('id', response.user_id);
    
                // Tampilkan modal
                $('#editUserModal').modal('show');
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    });
    
    // Submit form untuk update
    $('#editUserForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var userId = $('#editUserForm').data('id'); // Ambil ID dari atribut data-id
    
        $.ajax({
            url: '/users/' + userId, // Endpoint update
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response.success);
                $('#editDeptModal').modal('hide');
                //fetchDepartments(); // Refresh data department
                location.reload(); // Auto-refresh halaman
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    });
    
    $('#delete-selected').click(function () {
        const selectedIds = [];
        $('.delete-checkbox:checked').each(function () {
            selectedIds.push($(this).data('id'));
        });
    
        if (selectedIds.length === 0) {
            alert('Please select at least one item to delete.');
            return;
        }
    
        if (!confirm('Are you sure you want to delete the selected items?')) {
            return;
        }
    
        $.ajax({
            url: '{{ route("usersadmin.delete") }}', // Pastikan route delete ini sudah benar
            type: 'POST',
            data: {
                ids: selectedIds,
                _token: '{{ csrf_token() }}' // CSRF token untuk keamanan
            },
            success: function(response) {
                alert('Selected items have been deleted successfully.');
                fetchUsers(); // Refresh data tabel setelah penghapusan
            },
            error: function(xhr, status, error) {
                console.error('Error deleting selected items:', error);
                alert('An error occurred while deleting the selected items.');
            }
        });
    });

    
    
});
    
    
</script>



@endsection
