
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">{{$template_name}}</h1>
                                <!--<ol class="breadcrumb">-->
                                <!--    <li class="breadcrumb-item"><a href="javascript:void(0);">Users</a></li>-->
                                <!--    <li class="breadcrumb-item active" aria-current="page">List</li>-->
                                <!--</ol>-->
                               <div class="ms-auto"> <!-- Menempatkan tombol di sisi kanan -->
                                    <a href="{{route('templatedc.index')}}"><button  class="btn btn-warning me-2" ><i class="fe fe-arrow-left"></i>Back</button></a>
                                    <button class="btn btn-primary me-2" id="addlistdcButton">Add Checklist Item</button>
                                    <button class="btn btn-danger" id="delete-selected">Delete Checklist Item</button>
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
                                                                    <th>Category</th>
                                                                    <th>Sub Category</th>
                                                                    <th>Checklist Item</th>
                                                                    <th>Action</th>
                                                                    
                                                                </tr>
                                                            </thead>
                                                            <tbody id="checklistitemTableBody">
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
                        <div class="modal fade" id="addlistdcModal" tabindex="-1" aria-labelledby="addlistdcModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addListdcModalLabel">Add Checklist Item</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="addListdcForm" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="template_id" value="{{$template_id}}">
                                            <input type="hidden" name="site_id" value="{{$site_id}}">
                                            <div class="mb-3">
                                                <label for="dept name" class="form-label">Category Name</label>
                                                <select class="form-control" name="name_category_tempdc" id="name_category_tempdc">
                                                 <option>Select Category</option>
                                                    @foreach($categories as $category)
                                                    <option value="{{$category->category_id}}">{{$category->category_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                             <div class="mb-3">
                                                <label for="dept name" class="form-label">Sub Category Name</label>
                                                <select class="form-control" name="name_subcategory_tempdc" id="name_subcategory_tempdc" required>
                                                    
                                                </select>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="tele channel" class="form-label">Item Name</label>
                                                <input type="text" class="form-control" id="itemname_dc" name="itemname_dc" required>
                                            </div>
                                          
                                             <div class="mb-3">
                                                <label for="tele channel" class="form-label">Sort Order</label>
                                                <input type="text" class="form-control" id="sort_orderdc" name="sort_orderdc" required>
                                            </div>
                                           
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        
                        
                        <!-- Modal Edit Department -->
                        <div class="modal fade" id="editSubCategoryModal" tabindex="-1" aria-labelledby="editDeptLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editTempLabel">Edit Checklist Item </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="editSubCategoryForm" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT') <!-- Method untuk edit -->
                                    
                                    <div class="modal-body">
                                        <!-- Department Name -->
                                        <div class="mb-3">
                                                <label for="dept name" class="form-label">Checklist Item</label>
                                                <input type="text" class="form-control" name="itemname_dc_edit" id="itemname_dc_edit">
                                        </div>
                                          
                                        <div class="mb-3">
                                                <label for="tele channel" class="form-label">Sort Order</label>
                                                <input type="text" class="form-control" id="sort_orderdc_edit" name="sort_orderdc_edit">
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


    
    //Get Dropdown Department
     $(document).ready(function() {
        // Event listener untuk dropdown site
        $('#name_category_tempdc').on('change', function() {
            var categoryId = $(this).val();
            if (categoryId) {
                $.ajax({
                    url: '/admin/digitalchecklist/listcategory/' + categoryId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#name_subcategory_tempdc').empty();
                        $.each(data, function(key, value) {
                            $('#name_subcategory_tempdc').append('<option value="' + value.subcategory_id + '">' + value.subcategory_name + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching departments:', error);
                    }
                });
            } else {
                $('#name_subcategory_tempdc').empty();
            }
        });
     });


    // Tampilkan modal saat tombol Add User diklik
    document.getElementById('addlistdcButton').addEventListener('click', function() {
        new bootstrap.Modal(document.getElementById('addlistdcModal')).show();
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

     
     // Fungsi untuk menampilkan data user di tabel
    function fetchList() {
        var template_id = $('input[name="template_id"]').val();
        
       
        $.ajax({
            url: '{{ route("templatedc.datalist") }}',
            type: 'GET',
            dataType: 'json',
            data: {
            template_id: template_id  // Kirim template_id sebagai parameter
            },
            success: function(data) {
                let rows = '';
                data.forEach(template => {
                    rows += `
                        <tr>
                            <td><input type="checkbox" class="delete-checkbox" data-id="${template.item_id}"></td>
                            <td>${template.category_name}</td>
                            <td>${template.subcategory_name}</td>
                            <td>${template.item_name}</td>
                            <td>
                                <!-- Tambahkan action seperti Edit/Delete -->
                                <button class="btn btn-sm btn-primary edit-listchecklist-btn" data-id="${template.item_id}"><i class="fe fe-edit"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('#checklistitemTableBody').html(rows);
                
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
        
        fetchList();
 
 
    $('#addListdcForm').on('submit', function(e) {
    e.preventDefault();

    // Ambil form data termasuk file
    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        url: '{{ route("templatedc.storelist") }}', // Pastikan ini mengarah ke route yang benar
        data: formData,
        processData: false, // Jangan proses data
        contentType: false, // Jangan set content-type secara manual
        success: function(response) {
            alert(response.success);
            $('#addlistdcModal').modal('hide');
            $('#addListdcForm')[0].reset(); // Reset form
            fetchList(); // Refresh data user
        },
        error: function(xhr) {
            alert('Error: ' + xhr.responseJSON.message);
        }
    });
});


$(document).on('click', '.edit-listchecklist-btn', function() {
    var listId = $(this).data('id');
    
    // Ambil data department melalui AJAX
    $.ajax({
        url: '/admin/digitalchecklist/template/list/' + listId + '/edit', // Endpoint untuk mengambil data
        type: 'GET',
        success: function(response) {
            console.log(response);
            
            // Isi data ke dalam form
            $('#itemname_dc_edit').val(response.template.item_name);
            $('#sort_orderdc_edit').val(response.template.sort_order);

            // Simpan ID ke form sebagai atribut data-id
            $('#editSubCategoryForm').data('id', response.template.item_id);

            // Tampilkan modal
            $('#editSubCategoryModal').modal('show');
        },
        error: function(xhr) {
            alert('Error: ' + xhr.responseJSON.message);
        }
    });
});

// Submit form untuk update
$('#editSubCategoryForm').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    var departmentId = $('#editSubCategoryForm').data('id'); // Ambil ID dari atribut data-id

    $.ajax({
        url: '/admin/digitalchecklist/template/list/' + departmentId, // Endpoint update
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            alert(response.success);
            $('#editSubCategoryModal').modal('hide');
            fetchList(); 
            //fetchDepartments(); // Refresh data department
            //location.reload(); // Auto-refresh halaman
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
        alert('Please select department(s) to delete.');
        return;
    }

    if (!confirm('Are you sure you want to delete the selected items?')) {
        return;
    }

    $.ajax({
        url: '{{ route("templatedc.deletelist") }}', // Pastikan route delete ini sudah benar
        type: 'POST',
        data: {
            ids: selectedIds,
            _token: '{{ csrf_token() }}' // CSRF token untuk keamanan
        },
        success: function(response) {
            alert('Selected items have been deleted successfully.');
            fetchList(); // Refresh data tabel setelah penghapusan
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
