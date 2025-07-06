@extends('layouts.master')

@section('styles')
<style>
    .digital-checklist-card {
        transition: all 0.3s ease;
        border-radius: 8px;
        border: 1px solid #e0e6ed;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .digital-checklist-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    .digital-checklist-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 15px;
    }
    .digital-checklist-title {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
        line-height: 1.3;
    }
    .icon-primary {
        background-color: rgba(70, 127, 207, 0.1);
        color: #467fcf;
    }
    .icon-secondary {
        background-color: rgba(134, 142, 150, 0.1);
        color: #868e96;
    }
    .icon-success {
        background-color: rgba(50, 192, 104, 0.1);
        color: #32c068;
    }
    .card-body {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 2rem;
    }
</style>
@endsection

@section('content')
<!-- PAGE-HEADER -->
<div class="page-header">
    <h1 class="page-title">Digital Checklist</h1>
    <div class="ms-auto">
        <a href="{{ url()->previous() }}" class="btn btn-warning me-2">
            <i class="fe fe-arrow-left"></i> Back
        </a>
    </div>
</div>
<!-- PAGE-HEADER END -->

<!-- ROW-1 -->
<div class="row">
    <div class="col-xl-4 col-md-6">
        <a href="{{ route('digital-checklist.create') }}" class="text-decoration-none">
            <div class="card digital-checklist-card">
                <div class="card-body">
                    <div class="digital-checklist-icon icon-primary">
                        <i class="fe fe-plus fs-20"></i>
                    </div>
                    <h5 class="digital-checklist-title">Add Digital<br>Checklist</h5>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-md-6">
        <a href="{{ route('digital-checklist.index') }}" class="text-decoration-none">
            <div class="card digital-checklist-card">
                <div class="card-body">
                    <div class="digital-checklist-icon icon-secondary">
                        <i class="fe fe-list fs-20"></i>
                    </div>
                    <h5 class="digital-checklist-title">View Digital<br>Checklist</h5>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-4 col-md-6">
        <a href="{{ route('room-status.index') }}" class="text-decoration-none">
            <div class="card digital-checklist-card">
                <div class="card-body">
                    <div class="digital-checklist-icon icon-success">
                        <i class="fe fe-home fs-20"></i>
                    </div>
                    <h5 class="digital-checklist-title">Room<br>Status</h5>
                </div>
            </div>
        </a>
    </div>
</div>
<!-- ROW-1 CLOSED -->

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
@endsection