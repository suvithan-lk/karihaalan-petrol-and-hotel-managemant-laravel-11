<?php

namespace App\Http\Controllers;

use App\Models\MeterReading;
use Illuminate\Http\Request;

class PetrolMeterController extends Controller
{
    public function index()
    {
        $readings = MeterReading::all(); // Fetch all readings
        return view('petrolset.petrolmeter', compact('readings'));
    }

    public function show()
    {
        $readings = MeterReading::all();
        return view('petrolset.petrolmeterreadingform', compact('readings'));
    }

    public function store(Request $request)
    {
    // Validate the input
    $request->validate([
        'meter_reading' => 'required|numeric',
        'amount_received' => 'required|numeric',
        'proof_image' => 'required|array',  // Proof should be an array of files
        'proof_image.*' => 'image|mimes:jpeg,png,jpg|max:2048',  // Each file must be a valid image, max 2MB
    ]);

    // Store the proof images
    $proofImagePaths = [];
    if ($request->hasFile('proof_image')) {
        foreach ($request->file('proof_image') as $file) {
            $proofImagePaths[] = $file->store('meter-readings', 'public');
        }
    }

    // Save data to the database
    MeterReading::create([
        'meter_reading' => $request->meter_reading,
        'amount_received' => $request->amount_received,
        'proof_image' => json_encode($proofImagePaths), // Store paths as JSON array
    ]);

    // Redirect with success message
    return redirect()->route('petrolmeter')->with('success', 'Meter reading added successfully!');
}


    public function approveMeter($id)
{
    try {
        $meterReading = MeterReading::findOrFail($id); // Find the meter reading
        $meterReading->is_approved = true;            // Set it as approved
        $meterReading->save();                        // Save the changes

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}


    public function edit($id)
    {
        $meterReading = MeterReading::findOrFail($id); // Ensure the record exists

        // Prevent editing if the income is approved
    if ($meterReading->is_approved) {
        return redirect()->back()->with('error', 'You cannot edit an approved petrol meter.');
    }
        return view('petrolset.editpetrolmeter', compact('meterReading'));
    }

    public function update(Request $request, $id)
{
    $meterReading = MeterReading::findOrFail($id); // Ensure the record exists

    // Validate the input
    $request->validate([
        'meter_reading' => 'required|numeric',
        'amount_received' => 'required|numeric',
        'proof_image' => 'nullable|array',  // Optional array of images
        'proof_image.*' => 'image|mimes:jpeg,png,jpg|max:2048', // Each file must be a valid image
    ]);

    // Update meter reading and amount
    $meterReading->meter_reading = $request->meter_reading;
    $meterReading->amount_received = $request->amount_received;

    // Handle proof image upload if present
    if ($request->hasFile('proof_image')) {
        // Delete old images if they exist
        if ($meterReading->proof_image) {
            $existingImages = json_decode($meterReading->proof_image, true);
            foreach ($existingImages as $image) {
                if (\Storage::exists('public/' . $image)) {
                    \Storage::delete('public/' . $image);
                }
            }
        }

        // Store new images
        $proofImagePaths = [];
        foreach ($request->file('proof_image') as $file) {
            $proofImagePaths[] = $file->store('meter-readings', 'public');
        }

        // Update proof images in the database
        $meterReading->proof_image = json_encode($proofImagePaths);
    }

    // Save the updated record
    $meterReading->save();

    // Redirect with success message
    return redirect()->route('petrolmeter.form')->with('success', 'Meter reading updated successfully!');
}

}
