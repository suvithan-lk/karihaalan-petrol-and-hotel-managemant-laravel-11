<?php

namespace App\Http\Controllers;

use App\Models\MeterReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetrolMeterController extends Controller
{
    public function index()
    {
        $readings = MeterReading::latest()->get();
        return view('petrolset.petrolmeter', compact('readings'));
    }

    public function show()
    {
        $readings = MeterReading::latest()->get();
        return view('petrolset.petrolmeterreadingform', compact('readings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'meter_reading' => ['required', 'numeric', 'min:0'],
            'amount_received' => ['required', 'numeric', 'min:0.01'],
            'proof_image' => ['required', 'array', 'min:1'],
            'proof_image.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $proofImagePaths = [];
        foreach ($request->file('proof_image', []) as $file) {
            $proofImagePaths[] = $file->store('meter-readings', 'public');
        }

        MeterReading::create([
            'meter_reading' => $validated['meter_reading'],
            'amount_received' => $validated['amount_received'],
            'proof_image' => json_encode($proofImagePaths),
            'is_approved' => false,
        ]);

        return redirect()->route('petrolmeter')->with('success', 'Meter reading added successfully!');
    }

    public function approveMeter($id)
    {
        $meterReading = MeterReading::findOrFail($id);

        if ($meterReading->is_approved) {
            return response()->json(['success' => false, 'message' => 'Meter reading is already approved.'], 422);
        }

        $meterReading->update(['is_approved' => true]);
        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $meterReading = MeterReading::findOrFail($id);

        if ($meterReading->is_approved) {
            return redirect()->back()->with('error', 'You cannot edit an approved petrol meter.');
        }

        return view('petrolset.editpetrolmeter', compact('meterReading'));
    }

    public function update(Request $request, $id)
    {
        $meterReading = MeterReading::findOrFail($id);

        if ($meterReading->is_approved) {
            return redirect()->back()->with('error', 'You cannot update an approved petrol meter.');
        }

        $validated = $request->validate([
            'meter_reading' => ['required', 'numeric', 'min:0'],
            'amount_received' => ['required', 'numeric', 'min:0.01'],
            'proof_image' => ['nullable', 'array'],
            'proof_image.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $meterReading->meter_reading = $validated['meter_reading'];
        $meterReading->amount_received = $validated['amount_received'];

        if ($request->hasFile('proof_image')) {
            foreach (json_decode($meterReading->proof_image, true) ?: [] as $image) {
                Storage::disk('public')->delete($image);
            }

            $proofImagePaths = [];
            foreach ($request->file('proof_image', []) as $file) {
                $proofImagePaths[] = $file->store('meter-readings', 'public');
            }
            $meterReading->proof_image = json_encode($proofImagePaths);
        }

        $meterReading->save();
        return redirect()->route('petrolmeter.form')->with('success', 'Meter reading updated successfully!');
    }
}
