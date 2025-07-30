<?php

namespace App\Http\Controllers;

use App\Models\HotelIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelIncomeController extends Controller
{public function index()
    {
        // Fetch all hotel incomes and calculate the total income
        $incomes = HotelIncome::all();
        $totalIncome = HotelIncome::where('is_approved', true)->sum('amount');

        return view('hotel.income', compact('incomes', 'totalIncome')); // Pass incomes and totalIncome to the view
    }

    public function create()
    {
        $incomes = HotelIncome::all();
        $totalIncome = HotelIncome::sum('amount');
        return view('hotel.income', compact('incomes', 'totalIncome')); // No need to pass $incomes to the create view
    }

    public function store(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'amount' => 'required|numeric|min:0',
        'proof.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $proofImages = [];
    if ($request->hasFile('proof')) {
        foreach ($request->file('proof') as $file) {
            $filePath = $file->store('proof_images', 'public');
            $proofImages[] = $filePath;
        }
    }

    HotelIncome::create([
        'date' => $request->date,
        'amount' => $request->amount,
        'proof' => json_encode($proofImages), // Store proof as JSON
        'is_approved' => false,
    ]);

    return redirect()->route('hotelincome.index')->with('success', 'Hotel income added successfully.');
}


    public function approveIncome($id)
    {
        try {
            $income = HotelIncome::findOrFail($id); // Find the income
            $income->is_approved = true;      // Set it as approved
            $income->save();                  // Save the changes

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function edit($id)
{
    $income = HotelIncome::findOrFail($id);

     // Add authorization logic if necessary
     if (auth()->user()->role !== 'admin' && auth()->id() !== $income->user_id) {
        abort(403, 'Unauthorized access');
    }

    return view('hotel.incomeedit', compact('income'));
}

public function update(Request $request, $id)
{
    $income = HotelIncome::findOrFail($id);

    // Ensure user has permission to update this record
    if (auth()->user()->role !== 'admin' && auth()->id() !== $income->user_id) {
        abort(403, 'Unauthorized access');
    }

    // Validate input
    $validatedData = $request->validate([
        'date' => 'required|date',
        'amount' => 'required|numeric|min:0',
        'proof' => 'nullable|array', // Allow 'proof' to be an array of images
        'proof.*' => 'image|mimes:jpeg,png,jpg', // Validate each file as an image
    ]);

    // Update the income record with validated data
    $income->date = $validatedData['date'];
    $income->amount = $validatedData['amount'];

    // Handle proof images if present
    if ($request->hasFile('proof')) {
        // Delete old proof files if they exist
        if ($income->proof) {
            $existingProofs = json_decode($income->proof, true); // Get existing proof paths
            foreach ($existingProofs as $file) {
                Storage::delete('public/' . $file); // Delete old proof files
            }
        }

        // Store new proof images and get their paths
        $proofPaths = [];
        foreach ($request->file('proof') as $file) {
            $proofPaths[] = $file->store('proof_images', 'public');
        }

        // Save the paths as a JSON array
        $income->proof = json_encode($proofPaths);
    }

    // Save the updated record
    $income->save();

    // Redirect with success message
    return redirect()->route('hotelincome.index', ['id' => $income->id])
        ->with('success', 'Income record updated successfully.');
}


}
