<?php

namespace App\Http\Controllers;

use App\Models\HotelRoom;
use App\Models\RoomBooking;
use Illuminate\Http\Request;

class RoomBookingController extends Controller
{
    public function index(){
        $rooms = HotelRoom::all();
        // dd($rooms);
        return view("hotel.roombooking",compact("rooms"));
    }

    public function store(Request $request){

        $request->validate([
            'hotel_room_id' => 'required|exists:hotel_rooms,id',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:15',
            'booking_duration' => 'required|in:Full Day,Half Day,Hourly',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
           'total_price' => 'required|numeric',
        ]);

        $room = HotelRoom::findOrFail($request->hotel_room_id);




        RoomBooking::create([
            'hotel_room_id' => $request->hotel_room_id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'booking_duration' => $request->booking_duration,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'total_price' => $request->total_price,
            'notes' => $request->notes,


        ]);

        return redirect()->back()->with('success', 'Room booked successfully.');

    }
    public function show(){
        $bookings = RoomBooking::all();
        return view('hotel.viewroombooking', compact('bookings'));
    }

    public function approve($id)
{
    $booking = RoomBooking::findOrFail($id);

    if ($booking->is_approved) {
        return response()->json(['success' => false, 'message' => 'This booking is already approved.']);
    }

    $booking->is_approved = true;
    $booking->save();

    return response()->json(['success' => true, 'message' => 'Booking approved successfully.']);
}
public function edit($id)
{
    $booking = RoomBooking::findOrFail($id);
    // Check if the booking is already approved
    if ($booking->is_approved) {
        return redirect()->route('bookings.index')->with('error', 'You cannot edit an approved booking.');
    }

    return view('hotel.editbook', compact('booking'));
}

public function update(Request $request, $id)
{
    $request->validate([
            'customer_name' => 'nullable|string',
            'customer_phone' => 'nullable|string',
            'check_in' => 'nullable|date|after_or_equal:today',
            'check_out' => 'nullable|date|after:check_in',
            'total_price' => 'nullable|numeric',

    ]);

    $booking = RoomBooking::findOrFail($id);
    $booking->update($request->all());

    return redirect()->route('roombookings.show')->with('success', 'Booking updated successfully.');
}


}
