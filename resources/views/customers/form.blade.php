@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4 text-center">{{ isset($customer) ? 'Edit Customer' : 'Add New Customer' }}</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Check if we're editing an existing customer, else it's a create form -->
            <form action="{{ isset($customer) ? route('customers.update', $customer->id) : route('customers.store') }}" method="POST">
                @csrf
                @if(isset($customer))
                @method('PUT')
                @endif

                <!-- Name Field -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $customer->name ?? '') }}" required>
                    @error('name')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $customer->email ?? '') }}" required>
                    @error('email')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Phone Field (Optional) -->
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone (Optional)</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $customer->phone ?? '') }}">
                    @error('phone')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fa fa-save"></i> {{ isset($customer) ? 'Update Customer' : 'Save Customer' }}
                </button>
            </form>
        </div>
    </div>
    <a href="{{ route('customers.index') }}" class="btn btn-link mt-3">
        <i class="fa fa-arrow-left"></i> Back to Customers List
    </a>
</div>
@endsection