@extends('layouts.dashboard')
@section('title', 'Edit Booking')
@section('content')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="card">
                    <div class="card-inner">
                        <div class="card-head">
                            <h5 class="card-title">Edit Booking</h5>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        {{-- Error Message --}}
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="booking-form" action="{{ route('bookings.update', ['id' => $booking->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="customer_name" class="form-label">Customer Name:</label>
                                <input type="text" name="customer_name" id="customer_name"
                                       class="form-control @error('customer_name') is-invalid @enderror"
                                       value="{{ old('customer_name', $booking->customer_name) }}" >
                                @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="customer_phone" class="form-label">Customer Phone:</label>
                                <input type="text" name="customer_phone" id="customer_phone"
                                       class="form-control @error('customer_phone') is-invalid @enderror"
                                       value="{{ old('customer_phone', $booking->customer_phone) }}" >
                                @error('customer_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="check_in" class="form-label">Check-In Date:</label>
                                <input type="date" name="check_in" id="check_in"
                                       class="form-control @error('check_in') is-invalid @enderror"
                                       value="{{ old('check_in', $booking->check_in) }}" >
                                @error('check_in')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="check_out" class="form-label">Check-Out Date:</label>
                                <input type="date" name="check_out" id="check_out"
                                       class="form-control @error('check_out') is-invalid @enderror"
                                       value="{{ old('check_out', $booking->check_out) }}" >
                                @error('check_out')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="total_price" class="form-label">Total Price:</label>
                                <input type="number" step="0.01" name="total_price" id="total_price"
                                       class="form-control @error('total_price') is-invalid @enderror"
                                       value="{{ old('total_price', $booking->total_price) }}" >
                                @error('total_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Add file input for image upload -->
                            <div class="form-group">
                                <label for="image_upload" class="form-label">Upload Image:</label>
                                <input type="file" name="image_upload" id="image_upload" class="form-control">
                            </div>

                            <!-- Uploading status -->
                            <div id="uploading-status" class="alert alert-info" style="display:none;">Uploading...</div>

                            <!-- Submit Button -->
                            <button id="submit-btn" class="btn btn-secondary" type="submit">Save Changes</button>
                            <a href="{{ route('roombookings.show') }}" class="btn btn-secondary">Cancel</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Handle form submission
    document.getElementById('booking-form').addEventListener('submit', function(e) {
        // Disable the submit button
        document.getElementById('submit-btn').disabled = true;

        // Show uploading status
        document.getElementById('uploading-status').style.display = 'block';
    });
</script>

@endsection
