<?php

namespace App\Http\Controllers;

use App\Models\HotelRoom;
use Illuminate\Http\Request;

class HotelRoomController extends Controller
{
    // Method to show the form for adding a new hotel room
    public function create()
    {
        return view('hotel.addhotelroom');
    }

    // Method to store a new hotel room in the database
    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required|string|max:50|unique:hotel_rooms',
            'type_1' => 'required|in:A/C,Non-A/C',
            'price_full_day' => 'required|numeric|min:0',
            'price_half_day' => 'nullable|numeric|min:0',
            'price_hourly' => 'nullable|numeric|min:0',
            'is_available' => 'required|boolean',
        ]);

        HotelRoom::create([
            'room_number' => $request->room_number,
            'type_1' => $request->type_1,
            'price_full_day' => $request->price_full_day,
            'price_half_day' => $request->price_half_day,
            'price_hourly' => $request->price_hourly,
            'is_available' => $request->is_available,
        ]);

        return redirect()->back()->with('success', 'Hotel Room added successfully.');
    }

    // Method to list all hotel rooms
    public function index()
    {
        $rooms = HotelRoom::all();
        return view('hotel.viewrooms', compact('rooms'));
    }

    // Method to show a single room's details


    // Method to show the form for editing a hotel room
    public function edit($id)
    {
        $room = HotelRoom::findOrFail($id);
        return view('hotel.roomedit', compact('room'));
    }

    // Method to update the hotel room details
    public function update(Request $request, $id)
    {
        $request->validate([
            'room_number' => 'required|string|max:50',
            'type_1' => 'required|in:A/C,Non-A/C',
            'price_full_day' => 'required|numeric|min:0',
            'price_half_day' => 'nullable|numeric|min:0',
            'price_hourly' => 'nullable|numeric|min:0',
            'is_available' => 'required|boolean',
        ]);

        $room = HotelRoom::findOrFail($id);
        $room->update([
            'room_number' => $request->room_number,
            'type_1' => $request->type_1,
            'price_full_day' => $request->price_full_day,
            'price_half_day' => $request->price_half_day,
            'price_hourly' => $request->price_hourly,
            'is_available' => $request->is_available,
        ]);

        return redirect()->route('hotelrooms.index', $room->id)->with('success', 'Room updated successfully');
    }
}
