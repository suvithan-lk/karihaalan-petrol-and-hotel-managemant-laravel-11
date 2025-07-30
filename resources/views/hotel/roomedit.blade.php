@extends('layouts.dashboard')
@section('title', 'Edit Room')
@section('content')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="card">
                    <div class="card-inner">
                        <div class="card-head">
                            <h5 class="card-title">Edit Room</h5>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('hotelrooms.update', $room->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="room_number">Room Number:</label>
                                <input type="text" name="room_number" id="room_number" class="form-control" value="{{ old('room_number', $room->room_number) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="type_1">Room Type:</label>
                                <select name="type_1" id="type_1" class="form-control" required>
                                    <option value="A/C" {{ old('type_1', $room->type_1) == 'A/C' ? 'selected' : '' }}>A/C</option>
                                    <option value="Non-A/C" {{ old('type_1', $room->type_1) == 'Non-A/C' ? 'selected' : '' }}>Non-A/C</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="price_full_day">Price (Full Day):</label>
                                <input type="number" name="price_full_day" id="price_full_day" class="form-control" value="{{ old('price_full_day', $room->price_full_day) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="price_half_day">Price (Half Day):</label>
                                <input type="number" name="price_half_day" id="price_half_day" class="form-control" value="{{ old('price_half_day', $room->price_half_day) }}">
                            </div>

                            <div class="form-group">
                                <label for="price_hourly">Price (Hourly):</label>
                                <input type="number" name="price_hourly" id="price_hourly" class="form-control" value="{{ old('price_hourly', $room->price_hourly) }}">
                            </div>

                            <div class="form-group">
                                <label for="is_available">Is Available:</label>
                                <select name="is_available" id="is_available" class="form-control" required>
                                    <option value="1" {{ old('is_available', $room->is_available) == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('is_available', $room->is_available) == '0' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Room</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
