@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4 text-center">{{ isset($address) ? 'Edit Address' : 'Add New Address' }}</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Check if we're editing an existing address, else it's a create form -->
            <form action="{{ isset($address) ? route('addresses.update', $address->id) : route('addresses.store') }}" method="POST">
                @csrf
                @if(isset($address))
                @method('PUT')
                @endif

                <!-- Select Customer Field (Dropdown) -->
                <div class="mb-3">
                    <label for="customer_id" class="form-label">Select Customer</label>
                    <select class="form-control" id="customer_id" name="customer_id" required>
                        <option value="">Select a Customer</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}"
                            {{ old('customer_id', isset($address) ? $address->customer_id : '') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }} ({{ $customer->email }})
                        </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Address Fields -->
                <div class="mb-3">
                    <label for="address_line1" class="form-label">Address Line 1</label>
                    <input type="text" class="form-control" id="address_line1" name="address_line1" value="{{ old('address_line1', $address->address_line1 ?? '') }}" required>
                    @error('address_line1')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="address_line2" class="form-label">Address Line 2 (Optional)</label>
                    <input type="text" class="form-control" id="address_line2" name="address_line2" value="{{ old('address_line2', $address->address_line2 ?? '') }}">
                    @error('address_line2')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $address->city ?? '') }}" required>
                    @error('city')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="state" class="form-label">State</label>
                    <input type="text" class="form-control" id="state" name="state" value="{{ old('state', $address->state ?? '') }}" required>
                    @error('state')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="postal_code" class="form-label">Postal Code</label>
                    <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ old('postal_code', $address->postal_code ?? '') }}" required>
                    @error('postal_code')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fa fa-save"></i> {{ isset($address) ? 'Update Address' : 'Save Address' }}
                </button>
            </form>
        </div>
    </div>

    <a href="{{ route('customers.index') }}" class="btn btn-link mt-3">
        <i class="fa fa-arrow-left"></i> Back to Customers List
    </a>
</div>
@endsection