
@extends('layouts.master')

@section('styles')

@endsection

@section('content')

<!-- PAGE-HEADER -->
<div class="page-header">
  <h1 class="page-title">Sites Settings</h1>
  <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#siteModal" onclick="openSiteModal()">Add New Sites</button>
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
                    <th width="10%" class="text-center">Site ID</th>
                    <th width="20%">Site Name</th>
                    <th width="30%">Address</th>
                    <th width="20%" class="text-center">Site Initial</th>
                    <th width="20%" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($sites as $key => $value): ?>
                    <tr>
                      <td class="text-center">
                        #{{ $value->site_id }}
                      </td>
                      <td>
                        <b>{{ $value->site_name }}</b><br>
                        <small>{{ $value->site_fullname }}</small>
                      </td>
                      <td>
                        {{ $value->site_address }}
                      </td>
                      <td class="text-center">
                        {{ $value->initial }}
                      </td>
                      <td class="text-center">
                        <button class="btn btn-warning btn-sm"
                          data-bs-toggle="modal"
                          data-bs-target="#siteModal"
                          onclick="openSiteModal(this)"
                          data-id="{{ $value->site_id }}"
                          data-name="{{ $value->site_name }}"
                          data-fullname="{{ $value->site_fullname }}"
                          data-address="{{ $value->site_address }}"
                          data-initial="{{ $value->initial }}">
                          <span class="fe fe-edit"></span>
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
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

<!-- Modal Create/Edit Site -->
<div class="modal fade" id="siteModal" tabindex="-1" aria-labelledby="siteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="siteForm" action="{{ url('admin/sites/save') }}">
      @csrf

      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title text-primary fw-bold" id="siteModalLabel">Add/Edit Site</h3>
        </div>
        <div class="modal-body">

          <div class="mb-2">
            <input type="hidden" class="form-control" name="site_id" id="site_id">
            <label for="site_name" class="form-label">Site Name</label>
            <input type="text" class="form-control" id="site_name" name="site_name" placeholder="Enter the name of site" required>
          </div>
          <div class="mb-2">
            <label for="site_fullname" class="form-label">Site Fullname</label>
            <input type="text" class="form-control" id="site_fullname" name="site_fullname" placeholder="Enter the fullname of site" required>
          </div>
          <div class="mb-2">
            <label for="site_fullname" class="form-label">Site Address</label>
            <textarea class="form-control" id="site_address" name="site_address" required></textarea>
          </div>
          <div class="mb-2">
            <label for="initial" class="form-label">Site Initial</label>
            <input type="text" class="form-control" id="initial" name="initial" placeholder="Enter the initial of site" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Submit Data</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>


@endsection

@section('scripts')
<script>
function openSiteModal(button = null) {
  document.getElementById('siteForm').reset();
  if (button) {
    const id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name');
    const fullname = button.getAttribute('data-fullname');
    const address = button.getAttribute('data-address');
    const initial = button.getAttribute('data-initial');

    document.getElementById('site_id').value = id;
    document.getElementById('site_name').value = name;
    document.getElementById('site_fullname').value = fullname;
    document.getElementById('site_address').value = address;
    document.getElementById('initial').value = initial;

    document.getElementById('siteModalLabel').innerText = 'Edit Site';
  } else {
    document.getElementById('siteModalLabel').innerText = 'Add New Site';
  }
}
</script>
@endsection
