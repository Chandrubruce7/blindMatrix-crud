@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 text-primary">Customer Management</h1>
        <div>
            <a href="{{ route('customers.create') }}" class="btn btn-primary btn-md">
                <i class="fa fa-plus"></i> Add Customer
            </a>
            <a href="{{ route('customers.export') }}" class="btn btn-success btn-md ms-2">
                <i class="fa fa-download"></i> Export Customers
            </a>
            <a href="{{ route('customers.address') }}" class="btn btn-secondary btn-md ms-2">
                <i class="fa fa-map-marker"></i> Add Address
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <form action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data" class="input-group">
                @csrf
                <input type="file" name="file" class="form-control" required>
                <button class="btn btn-info">
                    <i class="fa fa-upload"></i> Import Excel
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @elseif(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover" id="customerTable">
                <thead class="table-dark">
                    <tr>
                        <th>S.no</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Added Address</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone ?? 'N/A' }}</td>
                        <td>{{count($customer->addresses)}}</td>
                        <td class="text-center">
                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning btn-sm text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-3">
            {{ $customers->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this customer?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#customerTable').DataTable({
            "paging": false,
            "lengthChange": false,
            "info": false,
            "processing": false,
            "responsive": true,
            "autoWidth": false,
            "language": {
                "search": "Filter records:",
            }
        });

        let deleteForm = null;

        $('.delete-btn').on('click', function() {
            deleteForm = $(this).closest('form');
            $('#deleteModal').modal('show');
        });

        $('#confirmDeleteBtn').on('click', function() {
            if (deleteForm) {
                deleteForm.submit();
            }
        });
    });
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
@endpush