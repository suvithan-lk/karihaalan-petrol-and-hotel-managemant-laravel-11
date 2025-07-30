<?php

namespace App\Http\Controllers;

use App\Models\HotelExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelExpenseController extends Controller
{
    public function create()
    {
        $expenses = HotelExpense::all();
        $totalExpense = HotelExpense::sum('amount');

        // return view('hotel.expense.index', compact('expenses', 'totalExpense'));/
        return view('hotel.expense', compact('expenses', 'totalExpense')); // View for creating a new hotel expense
    }

    /**
     * Store a newly created hotel expense in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'amount' => 'required|numeric|min:0',
        'proof' => 'required|array', // Allow proof to be an array of images
        'proof.*' => 'image|mimes:jpg,jpeg,png', // Validate each file as an image
    ]);

    // Handle multiple file uploads if present
    $proofPaths = [];
    if ($request->hasFile('proof')) {
        foreach ($request->file('proof') as $file) {
            $proofPaths[] = $file->store('proofs', 'public');
        }
    }

    // Store the hotel expense record
    HotelExpense::create([
        'date' => $request->date,
        'amount' => $request->amount,
        'proof' => json_encode($proofPaths), // Save the paths as a JSON array
    ]);

    // Redirect with success message
    return redirect()->route('hotel-expenses.index')->with('success', 'Hotel expense added successfully.');
}


    /**
     * Display the list of hotel expenses.
     */
    public function index()
    {
        $expenses = HotelExpense::all();
        $totalExpense = HotelExpense::where('is_approved', true)->sum('amount');

        return view('hotel.expense', compact('expenses', 'totalExpense'));
    }

    public function approveExpense($id)
{
    try {
        $expense = HotelExpense::findOrFail($id); // Find the expense
        $expense->is_approved = true;       // Mark it as approved
        $expense->save();                   // Save the changes

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

public function edit($id)
{
    $expense = HotelExpense::findOrFail($id);

    if (auth()->user()->role !== 'admin') {
        abort(403, 'Unauthorized access');
    }

    return view('hotel.expenseedit', compact('expense'));
}

public function update(Request $request, $id)
{
    $expense = HotelExpense::findOrFail($id);

    // Validate the input
    $validatedData = $request->validate([
        'date' => 'required|date',
        'amount' => 'required|numeric|min:0',
        'proof' => 'nullable|array', // Allow proof to be an array of images
        'proof.*' => 'image|mimes:jpeg,png,jpg|max:2048', // Validate each file as an image
    ]);

    $expense->date = $validatedData['date'];
    $expense->amount = $validatedData['amount'];

    // Handle proof images if present
    if ($request->hasFile('proof')) {
        // Delete old proof files if they exist
        if ($expense->proof) {
            $existingProofs = json_decode($expense->proof, true); // Get the existing proof paths
            foreach ($existingProofs as $file) {
                Storage::delete('public/' . $file); // Delete each old proof file
            }
        }

        // Store new proof images and get their paths
        $proofPaths = [];
        foreach ($request->file('proof') as $file) {
            $proofPaths[] = $file->store('proof_images', 'public');
        }

        // Save the paths as a JSON array
        $expense->proof = json_encode($proofPaths);
    }

    $expense->save();

    return redirect()->route('hotel-expenses.index', ['id' => $expense->id])
        ->with('success', 'Expense record updated successfully.');
}




}
