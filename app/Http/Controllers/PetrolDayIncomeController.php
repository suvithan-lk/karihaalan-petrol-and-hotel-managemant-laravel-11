<?php

namespace App\Http\Controllers;

use App\Models\PetrolDayIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

// use Stroage

class PetrolDayIncomeController extends Controller
{
    // Show the form to add a new income record
    public function create()
    {
        $incomes = PetrolDayIncome::all();
        $totalIncome = PetrolDayIncome::sum('amount');//lculate the total income

        return view('petrolset.income',compact('totalIncome','incomes'));
    }

    // Store the new income record in the database
    public function store(Request $request)
{
    // Validate the request
    $request->validate([
        'date' => 'required|date',
        'amount' => 'required|numeric',
        'proof' => 'required|array',  // proof should be an array of files
        'proof.*' => 'image|mimes:jpeg,png,jpg|max:2048',  // Validate each file
        'type' => 'required|string|max:255',  // Validate type if needed
    ]);

    // Initialize proof paths array
    $proofPaths = [];

    // Store the proof images
    if ($request->hasFile('proof')) {
        try {
            foreach ($request->file('proof') as $file) {
                $path = $file->store('income-proof', 'public');
                $proofPaths[] = $path;
                Log::info("File successfully stored at: " . $path);
            }
        } catch (\Exception $e) {
            Log::error("File upload failed: " . $e->getMessage());
            return redirect()->back()->withErrors(['proof' => 'Error uploading files. Please try again.']);
        }
    }

    // Create a new day-end income record
    PetrolDayIncome::create([
        'date' => $request->date,
        'amount' => $request->amount,
        'proof' => json_encode($proofPaths), // Store paths as JSON
        'type' => $request->type,
        'is_approved' => false, // Default value
    ]);

    // Redirect with success message
    return redirect()->route('dayendincome.create')->with('success', 'Income added successfully!');
}



    // Show all the income records with total income
    public function index()
    {
        $incomes = PetrolDayIncome::all();
        $totalIncome = PetrolDayIncome::where('is_approved', true)->sum('amount'); // Calculate the total income

        return view('petrolset.income', compact('incomes', 'totalIncome'));
    }

    public function approve($id)
    {
        try {
            $income = PetrolDayIncome::findOrFail($id); // Find the income
            $income->is_approved = true;      // Set it as approved
            $income->save();                  // Save the changes

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function edit($id)
    {
        $income = PetrolDayIncome::findOrFail($id);

        // Prevent editing if the income is approved
    if ($income->is_approved) {
        return redirect()->back()->with('error', 'You cannot edit an approved income.');
    }

        return view('petrolset.incomeedit', compact('income'));
    }

    public function update(Request $request, $id)
{
    $income = PetrolDayIncome::findOrFail($id);

    // Validate the input
    $request->validate([
        'date' => 'required|date',
        'amount' => 'required|numeric',
        'proof' => 'nullable|array',  // Allow proof to be an array of images
        'proof.*' => 'image|mimes:jpeg,png,jpg|max:2048',  // Validate each file
    ]);

    // Update the income date and amount
    $income->date = $request->date;
    $income->amount = $request->amount;

    // Handle multiple file uploads if present
    if ($request->hasFile('proof')) {
        // Delete old proof images if they exist
        if ($income->proof) {
            $existingProofs = json_decode($income->proof, true); // Decode JSON array of existing proof paths
            foreach ($existingProofs as $proof) {
                if (\Storage::exists('public/' . $proof)) {
                    \Storage::delete('public/' . $proof);
                }
            }
        }

        // Store new proof images
        $proofPaths = [];
        foreach ($request->file('proof') as $file) {
            $proofPaths[] = $file->store('proofs', 'public');
        }

        // Save the new proof images as a JSON array
        $income->proof = json_encode($proofPaths);
    }

    // Save the updated record
    $income->save();

    // Redirect with success message
    return redirect()->route('dayendincome.index')->with('success', 'Day End Income updated successfully!');
}


}
