@extends('layouts.dashboard')
@section('title', 'Hotel Rooms')
@section('content')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="card">
                    <div class="card-inner">
                        <div class="card-head">
                            <h5 class="card-title">Hotel Rooms</h5>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Room Number</th>
                                    <th>Room Type</th>
                                    <th>Price (Full Day)</th>
                                    <th>Price (Half Day)</th>
                                    <th>Price (Hourly)</th>
                                    <th>Availability</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rooms as $room)
                                    <tr>
                                        <td>{{ $room->room_number }}</td>
                                        <td>{{ $room->type_1 }}</td>
                                        <td>{{ $room->price_full_day }}</td>
                                        <td>{{ $room->price_half_day }}</td>
                                        <td>{{ $room->price_hourly }}</td>
                                        <td>{{ $room->is_available ? 'Yes' : 'No' }}</td>
                                        <td>
                                            <a href="{{ route('hotelrooms.edit', $room->id) }}" class="btn btn-primary">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
