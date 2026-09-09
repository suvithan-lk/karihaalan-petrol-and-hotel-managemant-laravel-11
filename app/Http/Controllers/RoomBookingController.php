<?php

namespace App\Http\Controllers;

use App\Models\HotelRoom;
use App\Models\RoomBooking;
use Illuminate\Http\Request;

class RoomBookingController extends Controller
{
    public function index()
    {
        $rooms = HotelRoom::query()->orderBy('id')->get();

        return view('hotel.roombooking', compact('rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_room_id' => ['required', 'integer', 'exists:hotel_rooms,id'],
            'customer_name' => ['required', 'string', 'max:100'],
            'customer_phone' => ['required', 'string', 'max:15'],
            'booking_duration' => ['required', 'in:Full Day,Half Day,Hourly'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'total_price' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        RoomBooking::create($validated);

        return redirect()->back()->with('success', 'Room booked successfully.');
    }

    public function show()
    {
        $bookings = RoomBooking::query()->latest()->get();

        return view('hotel.viewroombooking', compact('bookings'));
    }

    public function approve($id)
    {
        $booking = RoomBooking::findOrFail($id);

        if ($booking->is_approved) {
            return response()->json([
                'success' => false,
                'message' => 'This booking is already approved.',
            ], 422);
        }

        $booking->update(['is_approved' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Booking approved successfully.',
        ]);
    }

    public function edit($id)
    {
        $booking = RoomBooking::findOrFail($id);

        if ($booking->is_approved) {
            return redirect()->route('roombookings.show')
                ->with('error', 'You cannot edit an approved booking.');
        }

        return view('hotel.editbook', compact('booking'));
    }

    public function update(Request $request, $id)
    {
        $booking = RoomBooking::findOrFail($id);

        if ($booking->is_approved) {
            return redirect()->route('roombookings.show')
                ->with('error', 'You cannot update an approved booking.');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'customer_phone' => ['required', 'string', 'max:15'],
            'booking_duration' => ['required', 'in:Full Day,Half Day,Hourly'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'total_price' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $booking->update($validated);

        return redirect()->route('roombookings.show')
            ->with('success', 'Booking updated successfully.');
    }
}
