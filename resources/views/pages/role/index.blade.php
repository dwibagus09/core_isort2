
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Role</h1>
                                <!--<ol class="breadcrumb">-->
                                <!--    <li class="breadcrumb-item"><a href="javascript:void(0);">Users</a></li>-->
                                <!--    <li class="breadcrumb-item active" aria-current="page">List</li>-->
                                <!--</ol>-->
                                <button class="btn btn-primary mt-3" id="addRoleModalbtn">Add Role</button>
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
                                                                    <th>Nama Role</th>
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
                        <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addRoleModalLabel">Add Role</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="addRoleForm" action="{{ route('role.store') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="role_name" class="form-label">Role Name</label>
                                            <input type="text" class="form-control" id="role_name" name="role_name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Permissions</label>
                                            <div class="permissions-container">
                                                @foreach($modules as $module)
                                                    <div class="d-flex align-items-center mb-3">
                                                        <!-- Nama Menu -->
                                                        <strong class="me-3" style="min-width: 150px;">{{ $module->menu_name }}</strong>
                                                        
                                                        <!-- Checkbox Read -->
                                                        <div class="form-check me-2">
                                                            <input class="form-check-input" type="checkbox" name="permissions[{{ $module->module_id }}][]" value="read" id="read_{{ $module->module_id }}">
                                                            <label class="form-check-label" for="read_{{ $module->module_id }}">Read</label>
                                                        </div>
                                                        
                                                        <!-- Checkbox Write -->
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="permissions[{{ $module->module_id }}][]" value="write" id="write_{{ $module->module_id }}">
                                                            <label class="form-check-label" for="write_{{ $module->module_id }}">Write</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                        
                   <!-- Modal Edit Role -->
                    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editRoleLabel">Edit Role</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="" method="POST" id="editRoleForm">
                                @csrf
                                @method('PUT')
                
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="role_name">Role Name</label>
                                        <input type="text" class="form-control" id="role_name_edit" name="role_name" required>
                                    </div>
                
                                    <div class="form-group">
                                    <label>Permissions</label>
                                    <div class="checkbox">
                                        @foreach($modules as $module)
                                        <div class="d-flex align-items-center mb-3">
                                            <!-- Nama Menu -->
                                            <strong class="me-3" style="min-width: 150px;">{{ $module->menu_name }}</strong>
                                            
                                            <!-- Checkbox Read -->
                                            <div class="form-check me-2">
                                                <input type="checkbox" 
                                                       name="permissions_edit[{{ $module->module_id }}][]" 
                                                       value="read" 
                                                       id="read_{{ $module->module_id }}" 
                                                       {{ isset($permissions[$module->module_id]) && in_array('read', $permissions[$module->module_id]) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="read_{{ $module->module_id }}">Read</label>
                                            </div>
                                            
                                            <!-- Checkbox Write -->
                                            <div class="form-check">
                                                <input type="checkbox" 
                                                       name="permissions_edit[{{ $module->module_id }}][]" 
                                                       value="write" 
                                                       id="write_{{ $module->module_id }}" 
                                                       {{ isset($permissions[$module->module_id]) && in_array('write', $permissions[$module->module_id]) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="write_{{ $module->module_id }}">Write</label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
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
    document.getElementById('addRoleModalbtn').addEventListener('click', function() {
        new bootstrap.Modal(document.getElementById('addRoleModal')).show();
    });

     // Fungsi untuk menampilkan data user di tabel
    function fetchUsers() {
        $.ajax({
            url: '{{ route("role.data") }}',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let rows = '';
                data.forEach(role => {
                    rows += `
                        <tr>
                            <td>${role.role}</td>
                            <td>
                            <!-- Edit Button -->
                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editRoleModal" onclick="setEditData(${role.role_id})">Edit</button>

                            <!-- Delete Button -->
                           <button class="btn btn-sm btn-danger" onclick="deleteRole(${role.role_id})">Delete</button>
                        </td>
                        </tr>
                    `;
                });
                $('#userTableBody').html(rows);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching user data:', error);
            }
        });
    }
    
    function setEditData(role_id) {
    $.ajax({
        url: `/role/${role_id}/data`, // Mengambil data role dan permissions
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.error) {
                alert(data.error);
                return;
            }

            // Isi modal dengan data role
            $('#role_name_edit').val(data.role.role);

            // Reset semua checkbox jika ada
            $('input[name^="permissions"]').prop('checked', false);
            
            // Isi checkbox berdasarkan data permissions
            $.each(data.permissions, function(moduleId, perms) {
                if (perms.includes('read')) {
                    $(`input[name="permissions_edit[${moduleId}][]"][value="read"]`).prop('checked', true);
                }
                if (perms.includes('write')) {
                    $(`input[name="permissions_edit[${moduleId}][]"][value="write"]`).prop('checked', true);
                }
            });

            // Update action URL form edit
            $('#editRoleForm').attr('action', `/role/${role_id}`);

            // Tampilkan modal menggunakan Bootstrap 5 API
            const editRoleModal = new bootstrap.Modal(document.getElementById('editRoleModal'));
            editRoleModal.show();
        },
        error: function(xhr, status, error) {
            console.error('Error fetching role data:', error);
        }
    });
}



    
    function deleteRole(id) {
        if (confirm('Apakah Anda yakin ingin menghapus role ini?')) {
            fetch(`/role/delete/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => {
                if (response.ok) {
                    alert('Role berhasil dihapus.');
                    location.reload();
                } else {
                    alert('Terjadi kesalahan saat menghapus.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
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
            url: '{{ route("users.store") }}', // Pastikan ini mengarah ke route yang benar
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
    
});

    
    
    
</script>



@endsection
