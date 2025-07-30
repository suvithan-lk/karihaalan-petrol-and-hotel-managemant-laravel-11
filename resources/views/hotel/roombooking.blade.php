@extends('layouts.dashboard')
@section('title', 'Book a Room')
@section('content')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="card">
                    <div class="card-inner">
                        <div class="card-head">
                            <h5 class="card-title">Book a Room</h5>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('roombookings.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="hotel_room_id" class="form-label">Room:</label>
                                <select name="hotel_room_id" id="hotel_room_id" class="form-control @error('hotel_room_id') is-invalid @enderror" required>
                                    <option value="">Select Room</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('hotel_room_id') == $room->id ? 'selected' : '' }}>
                                            Room {{ $room->room_number }} - {{ $room->type_1 }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hotel_room_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="customer_name" class="form-label">Customer Name:</label>
                                <input type="text" name="customer_name" id="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name') }}" required>
                                @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="customer_phone" class="form-label">Customer Phone:</label>
                                <input type="text" name="customer_phone" id="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" value="{{ old('customer_phone') }}" required>
                                @error('customer_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="booking_duration" class="form-label">Booking Duration:</label>
                                <select name="booking_duration" id="booking_duration" class="form-control @error('booking_duration') is-invalid @enderror" required>
                                    <option value="">Select Duration</option>
                                    <option value="Full Day" {{ old('booking_duration') == 'Full Day' ? 'selected' : '' }}>Full Day</option>
                                    <option value="Half Day" {{ old('booking_duration') == 'Half Day' ? 'selected' : '' }}>Half Day</option>
                                    <option value="Hourly" {{ old('booking_duration') == 'Hourly' ? 'selected' : '' }}>Hourly</option>
                                </select>
                                @error('booking_duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            {{-- check in --}}
                            <div class="form-group">
                                <label for="check_in" class="form-label">Check-In:</label>
                                <input
                                    type="datetime-local"
                                    name="check_in"
                                    id="check_in"
                                    class="form-control @error('check_in') is-invalid @enderror"
                                    value="{{ old('check_in') }}"
                                    required>
                                @error('check_in')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- check out --}}
                            <div class="form-group">
                                <label for="check_out" class="form-label">Check-Out:</label>
                                <input
                                    type="datetime-local"
                                    name="check_out"
                                    id="check_out"
                                    class="form-control @error('check_out') is-invalid @enderror"
                                    value="{{ old('check_out') }}"
                                    required>
                                @error('check_out')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="total_price" class="form-label">Total Cost:</label>
                                <input type="text" name="total_price" id="total_price" class="form-control @error('total_price') is-invalid @enderror" value="{{ old('total_price') }}" required>
                                @error('total_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Book Room</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
