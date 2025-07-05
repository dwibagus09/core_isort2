@extends('layouts.master')

@section('styles')
<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .table th:nth-child(1), .table td:nth-child(1) {
        width: 5%;
    }
    .table th:nth-child(2), .table td:nth-child(2) {
        width: 10%;
    }
    .table th:nth-child(3), .table td:nth-child(3) {
        width: 15%;
    }
    .table th:nth-child(4), .table td:nth-child(4) {
        width: 50%;
    }
    .table th:nth-child(5), .table td:nth-child(5) {
        width: 20%;
    }
    .vertical-center {
      display: flex;
      flex-direction: column;
      justify-content: center;
      height: 100%;
    }
    
    .current-worker {
    color: #6c757d; /* Warna abu-abu */
    font-style: italic;
    background-color: #f8f9fa !important;
}

optgroup {
    font-weight: bold;
    color: #495057;
    padding: 5px 0;
}
</style>
@endsection

@section('content')
<!-- PAGE-HEADER -->
<div class="page-header">
    <h1 class="page-title">Work Orders</h1>
    <div class="ms-auto">
        <!--<button class="btn btn-primary" id="add-work-order-btn">-->
        <!--    <i class="fe fe-plus"></i> Add Work Order-->
        <!--</button>-->
        <button class="btn btn-danger" id="delete-selected" style="display:none">
            <i class="fe fe-trash"></i> Delete Selected
        </button>
    </div>
</div>
<!-- PAGE-HEADER END -->

<!-- ROW-2 -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="grid-margin">
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap align-items-center">
                        <thead class="thead-light">
                            <tr>
                                <th><input type="checkbox" id="select-all"></th>
                                <th>ID</th>
                                <th>Kaizen ID</th>
                                <th>Details</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($workOrders as $workOrder)
                            <tr>
                                <td><input type="checkbox" class="delete-checkbox" data-id="{{ $workOrder->wo_id }}"></td>
                                <td>WO-{{ str_pad($workOrder->wo_id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>KZ-{{ str_pad($workOrder->kaizen_id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <div class="d-flex">
                                        <div class="vertical-center">
                                          
                                            <div><strong>Worker(s):</strong> 
                                                @if(isset($workersData[$workOrder->wo_id]))
                                                    @foreach($workersData[$workOrder->wo_id] as $worker)
                                                        <span class="badge bg-info">{{ $worker['name'] }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">No worker assigned</span>
                                                @endif
                                            </div>
                                            <div><strong>Schedule:</strong> 
                                                {{ \Carbon\Carbon::parse($workOrder->start_scheduled_date)->format('d M Y H:i') }} - 
                                                {{ \Carbon\Carbon::parse($workOrder->end_scheduled_date)->format('d M Y H:i') }}
                                            </div>
                                            <div><strong>Expected Time:</strong> 
                                                {{ $workOrder->expected_work_time }} 
                                                @if($workOrder->expected_work_time2 == 0) mins
                                                @elseif($workOrder->expected_work_time2 == 1) hours
                                                @else days @endif
                                            </div>
                                            <div><strong>Remark:</strong> {{ Str::limit($workOrder->assigned_remark, 50) }}</div>
                                            <div><strong>Status:</strong> {{ $workOrder->status }}</div>
                                            <div><strong>Attachment:</strong> 
                                            @if(isset($attachments[$workOrder->wo_id]))
                                            <div class="mt-2 d-flex flex-wrap">
                                                @foreach($attachments[$workOrder->wo_id] as $attachment)
                                                    @php
                                                        $extension = strtolower(pathinfo($attachment->filename, PATHINFO_EXTENSION));
                                                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                                                    @endphp
                                                    
                                                    @if(in_array($extension, $imageExtensions))
                                                        <a href="{{ asset('/'.$attachment->filename) }}" 
                                                           data-lightbox="attachment-{{ $workOrder->wo_id }}" 
                                                           data-title="Attachment for WO-{{ $workOrder->wo_id }}"
                                                           class="me-2 mb-2">
                                                            <img src="{{ asset($attachment->filename) }}" 
                                                                 class="img-thumbnail" 
                                                                 style="max-width: 250px; max-height: 250px;">
                                                        </a>
                                                    @else
                                                        <a href="{{ asset($attachment->filename) }}" 
                                                           target="_blank" 
                                                           class="btn btn-sm btn-outline-secondary me-2 mb-2">
                                                            <i class="fe fe-file"></i> {{ $attachment->filename }}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">No attachments</span>
                                        @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($workOrder->approved == 1)
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-work-order-btn" 
                                            data-id="{{ $workOrder->wo_id }}">
                                        <i class="fe fe-edit"></i>
                                    </button>
                                    <!--<button class="btn btn-sm btn-danger delete-work-order-btn" -->
                                    <!--        data-id="{{ $workOrder->wo_id }}">-->
                                    <!--    <i class="fe fe-trash"></i>-->
                                    <!--</button>-->
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{ $workOrders->links() }}
    </div>
</div>
<!-- ROW-2 CLOSED -->

<!-- Add Work Order Modal -->
<div class="modal fade" id="addWorkOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Work Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addWorkOrderForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Site</label>
                            <div class="input-group">
                                <input type="hidden" name="site_id" id="site_id">
                                <input type="text" class="form-control" id="name_site" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Department</label>
                            <div class="input-group">
                                <input type="hidden" name="department_id" id="department_id">
                                <input type="text" class="form-control" id="department_name" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kaizen ID</label>
                                <select class="form-control" name="kaizen_id" id="kaizen_select" required>
                                <option value="">Select Kaizen</option>
                                @foreach($kaizens as $kaizen)
                                    <option value="{{ $kaizen->kaizen_id }}" 
                                            data-department-id="{{ $kaizen->department_id }}"
                                            data-department-name="{{ $kaizen->department->department_name ?? '' }}"
                                            data-site-id="{{ $kaizen->site_id }}"
                                            data-site-name="{{ $kaizen->site->site_name ?? '' }}">
                                        KZ-{{ str_pad($kaizen->kaizen_id, 4, '0', STR_PAD_LEFT) }} - {{ $kaizen->description }}
                                    </option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Worker</label>
                                <input type="text" class="form-control" name="worker" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Start Scheduled Date</label>
                                <input type="datetime-local" class="form-control" name="start_scheduled_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">End Scheduled Date</label>
                                <input type="datetime-local" class="form-control" name="end_scheduled_date" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Expected Work Time</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="expected_work_time" required>
                                    <select class="form-select" name="expected_work_time2" required>
                                        <option value="0">Minutes</option>
                                        <option value="1">Hours</option>
                                        <option value="2">Days</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Approved</label>
                                <select class="form-select" name="approved" required>
                                    <option value="0">Pending</option>
                                    <option value="1">Approved</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Assigned Remark</label>
                        <textarea class="form-control" name="assigned_remark" rows="3" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Assigned Date</label>
                                <input type="datetime-local" class="form-control" name="assigned_date" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Executed Date</label>
                                <input type="datetime-local" class="form-control" name="executed_date" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Finish Date</label>
                                <input type="datetime-local" class="form-control" name="finish_date" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Work Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Work Order Modal -->
<div class="modal fade" id="editWorkOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Work Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editWorkOrderForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="wo_id" id="edit_wo_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Site ID</label>
                                <select class="form-control" name="site_id" id="edit_site_id" required>
                                    @foreach($sites as $site)
                                        <option value="{{ $site->site_id }}">{{ $site->site_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Worker</label>
                                <select class="form-control" id="edit_worker" name="worker[]" multiple="multiple">
                                    <option>Select New Worker</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Start Scheduled Date</label>
                                <input type="datetime-local" class="form-control" name="start_scheduled_date" id="edit_start_scheduled_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">End Scheduled Date</label>
                                <input type="datetime-local" class="form-control" name="end_scheduled_date" id="edit_end_scheduled_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Expected Work Time</label>
                                <input type="number" class="form-control" name="expected_work_time" id="edit_expected_work_time" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Time Unit</label>
                                <select class="form-control" name="expected_work_time2" id="edit_expected_work_time2" required>
                                    <option value="0">Minutes</option>
                                    <option value="1">Hours</option>
                                    <option value="2">Days</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remark</label>
                        <textarea class="form-control" name="assigned_remark" id="edit_assigned_remark" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Approval Status</label>
                        @php
                            $approvedStatus = isset($workOrder) ? $workOrder->approved : 0;
                        @endphp
                        
                        <select class="form-control" name="approved" id="edit_approved" required>
                            <option value="0" {{ $approvedStatus == 0 ? 'selected' : '' }}>Not Approved</option>
                            <option value="1" {{ $approvedStatus == 1 ? 'selected' : '' }}>Approved</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Work Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Tampilkan modal tambah
        $('#add-work-order-btn').click(function() {
            $('#addWorkOrderModal').modal('show');
        });

        $('#kaizen_select').change(function() {
        var selectedOption = $(this).find('option:selected');
        
        if (selectedOption.val()) {
            // Set department
            $('#department_id').val(selectedOption.data('department-id'));
            $('#department_name').val(selectedOption.data('department-name'));
            
            // Set site
            $('#site_id').val(selectedOption.data('site-id'));
            $('#name_site').val(selectedOption.data('site-name'));
        } else {
            $('#department_id').val('');
            $('#department_name').val('');
            $('#site_id').val('');
            $('#name_site').val('');
        }
    });

        // Submit form tambah
        $('#addWorkOrderForm').submit(function(e) {
            e.preventDefault();
            
            $.ajax({
                url: '{{ route("wo.store") }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if(response.success) {
                        $('#addWorkOrderModal').modal('hide');
                        $('#addWorkOrderForm')[0].reset();
                        Swal.fire('Success!', response.message, 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMsg = '';
                    
                    $.each(errors, function(key, value) {
                        errorMsg += value[0] + '\n';
                    });
                    
                    Swal.fire('Error!', errorMsg, 'error');
                }
            });
        });

        // Tampilkan modal edit
$(document).on('click', '.edit-work-order-btn', function() {
    var woId = $(this).data('id');
    
    $.ajax({
        url: '/admin/kaizen/wo/' + woId + '/edit',
        type: 'GET',
        success: function(response) {
            // Isi form edit dengan data yang ada
            $('#edit_wo_id').val(response.wo_id);
            $('#edit_site_id').val(response.site_id);
            
            // Load workers based on site_id
            loadCurrentWorker(response.worker, response.site_id);
            
            // Format datetime untuk input datetime-local
            function formatDateTimeForInput(datetime) {
                if (!datetime) return '';
                var date = new Date(datetime);
                return date.toISOString().slice(0, 16);
            }
            
            $('#edit_start_scheduled_date').val(formatDateTimeForInput(response.start_scheduled_date));
            $('#edit_end_scheduled_date').val(formatDateTimeForInput(response.end_scheduled_date));
            $('#edit_expected_work_time').val(response.expected_work_time);
            $('#edit_expected_work_time2').val(response.expected_work_time2);
            $('#edit_assigned_remark').val(response.assigned_remark);
             $('#edit_approved').val(response.approved);
            
            $('#editWorkOrderModal').modal('show');
        },
        error: function(xhr) {
            Swal.fire('Error!', 'Failed to load work order data', 'error');
        }
    });
});

// // Function to load workers based on site_id
// function loadWorkers(siteId, selectedWorker = null) {
//     $.ajax({
//         url: '/admin/kaizen/get-workers',
//         type: 'GET',
//         data: { site_id: siteId },
//         success: function(response) {
//             var workerDropdown = $('#edit_worker');
//             workerDropdown.empty();
            
//             if (response.length > 0) {
//                 $.each(response, function(index, worker) {
//                     workerDropdown.append(
//                         $('<option></option>').val(worker.id).text(worker.name)
//                     );
//                 });
                
//                 if (selectedWorker) {
//                     workerDropdown.val(selectedWorker);
//                 }
//             } else {
//                 workerDropdown.append(
//                     $('<option></option>').val('').text('No workers available')
//                 );
//             }
//         },
//         error: function(xhr) {
//             console.error('Error loading workers');
//         }
//     });
// }

// Fungsi untuk memuat data worker saat ini + opsi lainnya
// function loadCurrentWorker(currentWorkerId, siteId) {
//     // 1. Ambil data worker yang sudah dipilih
//     $.get('/admin/kaizen/get-worker/' + currentWorkerId, function(currentWorker) {
//         // 2. Jika worker ditemukan
//         if (currentWorker.success && currentWorker.data) {
//             // 3. Buat opsi pertama dengan worker yang sudah dipilih
//             var options = `<option value="${currentWorker.data.user_id}" selected>${currentWorker.data.name}</option>`;
            
//             // 4. Ambil worker lain dari site yang sama (jika ada siteId)
//             if (siteId) {
//                 $.get('/admin/kaizen/get-workers', { site_id: siteId }, function(allWorkers) {
//                     // 5. Tambahkan worker lainnya
//                     allWorkers.forEach(function(worker) {
//                         if (worker.user_id != currentWorkerId) {
//                             options += `<option value="${worker.user_id}">${worker.name}</option>`;
//                         }
//                     });
                    
//                     // 6. Update dropdown
//                     $('#edit_worker').html(options);
//                 });
//             } else {
//                 // 7. Jika tidak ada siteId, cukup tampilkan worker saat ini
//                 $('#edit_worker').html(options);
//             }
//         } else {
//             // 8. Jika worker tidak ditemukan
//             $('#edit_worker').html('<option value="">Worker tidak ditemukan</option>');
//         }
//     });
// }

// function loadCurrentWorker(currentWorkerIds, siteId) {
//     // Convert single worker ID to array for backward compatibility
//     if (!Array.isArray(currentWorkerIds)) {
//         currentWorkerIds = [currentWorkerIds];
//     }

//     // 1. Get all workers from the site
//     if (siteId) {
//         $.get('/admin/kaizen/get-workers', { site_id: siteId }, function(allWorkers) {
//             // 2. Create options
//             var options = '';
            
//             // 3. First add selected workers (preserve order if possible)
//             currentWorkerIds.forEach(function(workerId) {
//                 var worker = allWorkers.find(w => w.user_id == workerId);
//                 if (worker) {
//                     options += `<option value="${worker.user_id}" selected>${worker.name}</option>`;
//                 }
//             });
            
//             // 4. Then add other workers from the same site
//             allWorkers.forEach(function(worker) {
//                 if (!currentWorkerIds.includes(worker.user_id)) {
//                     options += `<option value="${worker.user_id}">${worker.name}</option>`;
//                 }
//             });
            
//             // 5. Update dropdown (make sure it's a multiple select)
//             $('#edit_worker').html(options).attr('multiple', 'multiple');
//         });
//     } else {
//         // 6. If no siteId, just get the selected workers' info
//         var options = '';
//         var requests = currentWorkerIds.map(function(workerId) {
//             return $.get('/admin/kaizen/get-worker/' + workerId);
//         });
        
//         $.when.apply($, requests).then(function() {
//             for (var i = 0; i < arguments.length; i++) {
//                 var response = arguments[i][0];
//                 if (response.success && response.data) {
//                     options += `<option value="${response.data.user_id}" selected>${response.data.name}</option>`;
//                 }
//             }
            
//             if (options === '') {
//                 options = '<option value="">No workers found</option>';
//             }
            
//             $('#edit_worker').html(options).attr('multiple', 'multiple');
//         });
//     }
// }

function loadCurrentWorker(currentWorkerIds, siteId) {
    // Convert to array jika single ID
    if (!Array.isArray(currentWorkerIds)) {
        currentWorkerIds = [currentWorkerIds];
    }

    if (siteId) {
        $.get('/admin/kaizen/get-workers', { site_id: siteId }, function(allWorkers) {
            var options = '';
            var currentWorkerNames = [];
            
            // 1. Tambahkan current workers terlebih dahulu
            currentWorkerIds.forEach(function(workerId) {
                var worker = allWorkers.find(w => w.user_id == workerId);
                if (worker) {
                    options += `<option value="${worker.user_id}" selected class="current-worker">${worker.name} (current)</option>`;
                    currentWorkerNames.push(worker.name); // Simpan nama worker yang sudah dipilih
                }
            });

            // 2. Filter workers yang belum dipilih (termasuk yang namanya sama)
            var availableWorkers = allWorkers.filter(function(worker) {
                return !currentWorkerNames.includes(worker.name); // Hapus yang namanya sudah ada
            });

            // 3. Tambahkan available workers
            if (availableWorkers.length > 0) {
                options += '<optgroup label="Available Workers">';
                availableWorkers.forEach(function(worker) {
                    options += `<option value="${worker.user_id}">${worker.name}</option>`;
                });
                options += '</optgroup>';
            }

            // 4. Update dropdown
            $('#edit_worker')
                .html(options)
                .attr('multiple', 'multiple')
                .trigger('change');
        });
    } else {
        // Fallback jika tidak ada siteId
        var options = currentWorkerIds.map(id => 
            `<option value="${id}" selected class="current-worker">Worker ID: ${id} (current)</option>`
        ).join('');
        
        $('#edit_worker')
            .html(options)
            .attr('multiple', 'multiple')
            .trigger('change');
    }
}

// Saat site diubah
$('#edit_site_id').change(function() {
    var siteId = $(this).val();
    if (siteId) {
        $.get('/admin/kaizen/get-workers', { site_id: siteId }, function(workers) {
            var options = '<option value="">Pilih Worker</option>';
            workers.forEach(function(worker) {
                options += `<option value="${worker.user_id}">${worker.name}</option>`;
            });
            $('#edit_worker').html(options);
        });
    }
});

// Submit form edit
$('#editWorkOrderForm').submit(function(e) {
    e.preventDefault();
    var woId = $('#edit_wo_id').val();
    
    $.ajax({
        url: '/admin/kaizen/wo/' + woId,
        type: 'PUT',
        data: $(this).serialize(),
        success: function(response) {
            if(response.success) {
                $('#editWorkOrderModal').modal('hide');
                Swal.fire('Success!', response.message, 'success');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            }
        },
        error: function(xhr) {
            var errors = xhr.responseJSON.errors;
            var errorMsg = '';
            
            $.each(errors, function(key, value) {
                errorMsg += value[0] + '\n';
            });
            
            Swal.fire('Error!', errorMsg, 'error');
        }
    });
});

        // Delete single work order
        // $(document).on('click', '.delete-work-order-btn', function() {
        //     var woId = $(this).data('id');
            
        //     Swal.fire({
        //         title: 'Are you sure?',
        //         text: "You won't be able to revert this!",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Yes, delete it!'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $.ajax({
        //                 url: '/work-orders/' + woId,
        //                 type: 'DELETE',
        //                 data: {
        //                     _token: '{{ csrf_token() }}'
        //                 },
        //                 success: function(response) {
        //                     if(response.success) {
        //                         Swal.fire('Deleted!', response.message, 'success');
        //                         setTimeout(function() {
        //                             location.reload();
        //                         }, 1500);
        //                     }
        //                 },
        //                 error: function(xhr) {
        //                     Swal.fire('Error!', 'Failed to delete work order', 'error');
        //                 }
        //             });
        //         }
        //     });
        // });

        // Select all checkbox
        $('#select-all').change(function() {
            $('.delete-checkbox').prop('checked', $(this).prop('checked'));
            toggleDeleteButton();
        });

        // Toggle delete selected button
        function toggleDeleteButton() {
            if($('.delete-checkbox:checked').length > 0) {
                $('#delete-selected').show();
            } else {
                $('#delete-selected').hide();
            }
        }

        $(document).on('change', '.delete-checkbox', function() {
            toggleDeleteButton();
        });

        // Delete selected work orders
        $('#delete-selected').click(function() {
            var selectedIds = [];
            $('.delete-checkbox:checked').each(function() {
                selectedIds.push($(this).data('id'));
            });
            
            if(selectedIds.length === 0) {
                Swal.fire('Info', 'Please select at least one work order', 'info');
                return;
            }
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to delete " + selectedIds.length + " work order(s)",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("wo.delete") }}',
                        type: 'POST',
                        data: {
                            ids: selectedIds,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if(response.success) {
                                Swal.fire('Deleted!', response.message, 'success');
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', 'Failed to delete selected work orders', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection