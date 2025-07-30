@extends('layouts.dashboard')
@section('title', 'Add Hotel Room')
@section('content')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="card">
                    <div class="card-inner">
                        <div class="card-head">
                            <h5 class="card-title">Add Hotel Room</h5>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('hotelrooms.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="room_number" class="form-label">Room Number:</label>
                                <input type="text" name="room_number" id="room_number" class="form-control @error('room_number') is-invalid @enderror" value="{{ old('room_number') }}" required>
                                @error('room_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="type_1" class="form-label">Room Type :(A/C or Non-A/C):</label>
                                <select name="type_1" id="type_1" class="form-control @error('type_1') is-invalid @enderror" required>
                                    <option value="">Select Type</option>
                                    <option value="A/C" {{ old('type_1') == 'A/C' ? 'selected' : '' }}>A/C</option>
                                    <option value="Non-A/C" {{ old('type_1') == 'Non-A/C' ? 'selected' : '' }}>Non-A/C</option>
                                </select>
                                @error('type_1')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="price_full_day" class="form-label">Price (Full Day):</label>
                                <input type="number" name="price_full_day" id="price_full_day" class="form-control @error('price_full_day') is-invalid @enderror" value="{{ old('price_full_day') }}" required>
                                @error('price_full_day')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="price_half_day" class="form-label">Price (Half Day):</label>
                                <input type="number" name="price_half_day" id="price_half_day" class="form-control @error('price_half_day') is-invalid @enderror" value="{{ old('price_half_day') }}">
                                @error('price_half_day')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="price_hourly" class="form-label">Price (Hourly):</label>
                                <input type="number" name="price_hourly" id="price_hourly" class="form-control @error('price_hourly') is-invalid @enderror" value="{{ old('price_hourly') }}">
                                @error('price_hourly')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="is_available" class="form-label">Is Available:</label>
                                <select name="is_available" id="is_available" class="form-control @error('is_available') is-invalid @enderror" required>
                                    <option value="1" {{ old('is_available') == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('is_available') == '0' ? 'selected' : '' }}>No</option>
                                </select>
                                @error('is_available')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Add Room</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
