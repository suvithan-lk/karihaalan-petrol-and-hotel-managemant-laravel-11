<?php

namespace App\Http\Controllers;

use App\Models\HotelRoom;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HotelRoomController extends Controller
{
    public function create()
    {
        return view('hotel.addhotelroom');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => ['required', 'string', 'max:50', 'unique:hotel_rooms,room_number'],
            'type_1' => ['required', 'in:A/C,Non-A/C'],
            'price_full_day' => ['required', 'numeric', 'gt:0'],
            'price_half_day' => ['nullable', 'numeric', 'gt:0'],
            'price_hourly' => ['nullable', 'numeric', 'gt:0'],
            'is_available' => ['required', 'boolean'],
        ]);

        HotelRoom::create($validated);

        return redirect()->back()->with('success', 'Hotel Room added successfully.');
    }

    public function index()
    {
        $rooms = HotelRoom::latest()->get();
        return view('hotel.viewrooms', compact('rooms'));
    }

    public function edit($id)
    {
        $room = HotelRoom::findOrFail($id);
        return view('hotel.roomedit', compact('room'));
    }

    public function update(Request $request, $id)
    {
        $room = HotelRoom::findOrFail($id);

        $validated = $request->validate([
            'room_number' => [
                'required', 'string', 'max:50',
                Rule::unique('hotel_rooms', 'room_number')->ignore($room->id),
            ],
            'type_1' => ['required', 'in:A/C,Non-A/C'],
            'price_full_day' => ['required', 'numeric', 'gt:0'],
            'price_half_day' => ['nullable', 'numeric', 'gt:0'],
            'price_hourly' => ['nullable', 'numeric', 'gt:0'],
            'is_available' => ['required', 'boolean'],
        ]);

        $room->update($validated);

        return redirect()->route('hotelrooms.index')->with('success', 'Room updated successfully');
    }
}
