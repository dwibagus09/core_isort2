
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Permission</h1>
                                <!--<ol class="breadcrumb">-->
                                <!--    <li class="breadcrumb-item"><a href="javascript:void(0);">Users</a></li>-->
                                <!--    <li class="breadcrumb-item active" aria-current="page">List</li>-->
                                <!--</ol>-->
                                <button class="btn btn-primary mt-3" id="addRoleModalbtn">Add Permission</button>
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
                                                                    <th>Module ID</th>
                                                                    <th>Menu</th>
                                                                    <th>Sub Menu</th>
                                                                    <th>Url</th>
                                                                    <th>Action</th>
                                                                    
                                                                </tr>
                                                            </thead>
                                                            <tbody id="permissionTableBody">
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
                                    <h5 class="modal-title" id="addRoleModalLabel">Add Permission</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="addRoleForm" action="{{ route('permission.store') }}" method="POST">
                                        @csrf
                                         <div class="mb-3">
                                            <label for="role_name" class="form-label">Menu Name</label>
                                            <input type="text" class="form-control" id="menu_name" name="menu_name" required>
                                        </div>
                                         <div class="mb-3">
                                            <label for="role_name" class="form-label">Sub Menu</label>
                                            <input type="text" class="form-control" id="submenu_name" name="submenu_name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="role_name" class="form-label">Url</label>
                                            <input type="text" class="form-control" id="url_menu" name="url_menu" required>
                                        </div>
                                        
                                        
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                        
                   <!-- Modal Edit Role -->
                    <div class="modal fade" id="editPermissionModal" tabindex="-1" aria-labelledby="editRoleLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editRoleLabel">Edit Permission</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="" method="POST" id="editPermissionForm">
                                @csrf
                                @method('PUT')
                
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="role_name">Menu Name</label>
                                        <input type="text" class="form-control" id="menu_name_edit" name="menu_name_edit" required>
                                    </div>
                                     <div class="form-group">
                                        <label for="role_name">Sub Menu Name</label>
                                        <input type="text" class="form-control" id="submenu_name_edit" name="submenu_name_edit">
                                    </div>
                                    <div class="form-group">
                                        <label for="role_name">URL</label>
                                        <input type="text" class="form-control" id="url_edit" name="url_edit" required>
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
            url: '{{ route("permission.data") }}',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let rows = '';
                data.forEach(permission => {
                    rows += `
                        <tr>
                            <td>${permission.module_id}</td>
                             <td>${permission.menu_name}</td>
                             <td>${permission.submenu_name}</td>
                             <td>${permission.url}</td>
                            <td>
                            <!-- Edit Button -->
                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editRoleModal" onclick="setEditData(${permission.module_id})">Edit</button>

                            <!-- Delete Button -->
                           <button class="btn btn-sm btn-danger" onclick="deleteRole(${permission.module_id})">Delete</button>
                        </td>
                        </tr>
                    `;
                });
                $('#permissionTableBody').html(rows);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching user data:', error);
            }
        });
    }
    
    function setEditData(module_id) {
    $.ajax({
        url: `/permission/${module_id}/data`, // Mengambil data role dan permissions
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.error) {
                alert(data.error);
                return;
            }

            // Isi modal dengan data role
            $('#menu_name_edit').val(data.permissions.menu_name);
            $('#submenu_name_edit').val(data.permissions.submenu_name);
            $('#url_edit').val(data.permissions.url);
           
            
            // Update action URL form edit
            $('#editPermissionForm').attr('action', `/permission/${module_id}`);

            // Tampilkan modal menggunakan Bootstrap 5 API
            const editRoleModal = new bootstrap.Modal(document.getElementById('editPermissionModal'));
            editRoleModal.show();
        },
        error: function(xhr, status, error) {
            console.error('Error fetching Permission data:', error);
        }
    });
}



    
    function deleteRole(id) {
        if (confirm('Apakah Anda yakin ingin menghapus role ini?')) {
            fetch(`/permission/delete/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => {
                if (response.ok) {
                    alert('Permission berhasil dihapus.');
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
 
    
});

    
    
    
</script>



@endsection
