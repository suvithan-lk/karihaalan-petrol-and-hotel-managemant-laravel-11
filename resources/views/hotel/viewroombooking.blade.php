@extends('layouts.dashboard')
@section('title', 'View Bookings')
@section('content')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="card">
                    <div class="card-inner">
                        <div class="card-head">
                            <h5 class="card-title">Booking Details</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Room</th>
                                        <th>Customer Name</th>
                                        <th>Customer Phone</th>
                                        <th>Duration</th>
                                        <th>Check-In</th>
                                        <th>Check-Out</th>
                                        <th>Total Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                    <tr>
                                        <td>{{ $booking->id }}</td>
                                        <td>{{ $booking->room->room_number ?? 'N/A' }} - {{ $booking->room->type_1 ?? 'N/A' }}</td>
                                        <td>{{ $booking->customer_name }}</td>
                                        <td>{{ $booking->customer_phone }}</td>
                                        <td>{{ $booking->booking_duration }}</td>
                                        <td>{{ $booking->check_in }}</td>
                                        <td>{{ $booking->check_out }}</td>
                                        <td>{{ $booking->total_price }}</td>
                                        <td>{{ $booking->is_approved ? 'Approved' : 'Pending' }}</td>
                                        <td>
                                            @if(!$booking->is_approved)
                                                <a href="javascript:void(0)"
                                                   onclick="approveBooking({{ $booking->id }})"
                                                   class="btn btn-success btn-sm">
                                                    Approve
                                                </a>
                                                <a href="{{ route('bookings.edit', ['id' => $booking->id]) }}"
                                                   class="btn btn-primary btn-sm">
                                                    Edit
                                                </a>
                                            @else
                                                <button disabled class="btn btn-dark btn-sm">Approved</button>
                                            @endif
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
</div>

<script>
    function approveBooking(bookingId) {
        fetch(`/approve-booking/${bookingId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Booking approved successfully!');
                location.reload();
            } else {
                alert(`Error: ${data.message || 'Could not approve booking'}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred. Please try again.');
        });
    }
</script>

@endsection
