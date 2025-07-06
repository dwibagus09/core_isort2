@extends('layouts.master')

@section('styles')
<style>
    .checklist-header {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 8px 8px 0 0;
        border-bottom: 1px solid #e0e6ed;
    }
    .checklist-form {
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        background-color: white;
    }
    .form-table {
        width: 100%;
        border-collapse: collapse;
    }
    .form-table td {
        padding: 1rem;
        border-bottom: 1px solid #e0e6ed;
    }
    .form-table tr:last-child td {
        border-bottom: none;
    }
    .form-label {
        font-weight: 600;
        color: #2c3e50;
        width: 30%;
    }
    .form-control {
        border: 1px solid #e0e6ed;
        border-radius: 4px;
        padding: 0.5rem 0.75rem;
        width: 100%;
    }
    .btn-save {
        background-color: #467fcf;
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 4px;
        border: none;
        font-weight: 600;
    }
    .btn-save:hover {
        background-color: #3a6db5;
    }
</style>
@endsection

@section('content')
<!-- PAGE-HEADER -->
<div class="page-header">
    <h1 class="page-title">Add Digital Checklist</h1>
    <div class="ms-auto">
        <a href="{{ route('digital-checklist.index') }}" class="btn btn-warning me-2">
            <i class="fe fe-arrow-left"></i> Back
        </a>
    </div>
</div>
<!-- PAGE-HEADER END -->

<!-- ROW-1 -->
<div class="row">
    <div class="col-12">
        <div class="checklist-form">
            <div class="checklist-header">
                <h4>Add Digital Checklist</h4>
            </div>
            
            <form action="{{ route('digital-checklist.store') }}" method="POST">
                @csrf
                <table class="form-table">
                    <tr>
                        <td class="form-label">Checklist</td>
                        <td>
                            <select class="form-control" name="checklist_type">
                                <option value="Villa Checklist 1" selected>Villa Checklist 1</option>
                                <option value="Villa Checklist 2">Villa Checklist 2</option>
                                <option value="Room Checklist">Room Checklist</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="form-label">Room Number</td>
                        <td>
                            <input type="text" class="form-control" name="room_number" value="510">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right; padding: 1.5rem;">
                            <button type="submit" class="btn-save">
                                Save & go to the next page
                            </button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<!-- ROW-1 CLOSED -->
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // You can add any JavaScript functionality here if needed
    });
</script>
@endsection