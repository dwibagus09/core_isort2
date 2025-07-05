
@extends('layouts.master')

@section('styles')

@endsection

@section('content')

<!-- PAGE-HEADER -->
<div class="page-header">
  <h1 class="page-title">Other Settings</h1>
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
                    <th>Module Name</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      Use real time picture for submitting kaizen
                    </td>
                    <td id="td_optionsettings">
                      <button class="btn btn-sm btn-{{ $statussetting->status == 1 ? 'success' : 'light' }}" onclick="setStatus('1')">Enabled</button>
                      <button class="btn btn-sm btn-{{ $statussetting->status == 0 ? 'success' : 'light' }}" onclick="setStatus('0')">Disabled</button>
                    </td>
                  </tr>
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

@endsection

@section('scripts')
<script>
function setStatus(status) {
  Swal.fire({
    title: 'Confirmation Action',
    text: "Are you sure to change this settings",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#907246',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, sure'
  }).then((result) => {
    if (result.isConfirmed) {
      $('#td_optionsettings').html(`<img src="{{ asset('build/assets/images/svgs/loader.svg') }}" >`)
      let url = `/admin/othersetting/update?status=${encodeURIComponent(status)}`;
      window.location.href = url;
    }
  });

}
</script>
@endsection
