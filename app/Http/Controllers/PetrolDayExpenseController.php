<?php

namespace App\Http\Controllers;
use App\Http\Controllers\HasFactory;
use App\Models\PetrolDayExpense;

use Illuminate\Http\Request;

class PetrolDayExpenseController extends Controller
{
   // Show the day-end expense form
   public function create()
   {
        $expenses = PetrolDayExpense::all();

        $totalExpense = PetrolDayExpense::sum('amount');


        return view('petrolset.expense', compact('totalExpense','expenses'));
   }

   // Store the day-end expense
   public function store(Request $request)
{
    // Validate the form input
    $request->validate([
        'date' => 'required|date',
        'amount' => 'required|numeric',
        'proof' => 'nullable|array',  // Proof should be an array of files
        'proof.*' => 'image|mimes:jpeg,png,jpg', // Each file should be an image
    ]);

    // Store proof images if available
    $proofPaths = [];
    if ($request->hasFile('proof')) {
        foreach ($request->file('proof') as $file) {
            $proofPaths[] = $file->store('proofs', 'public');
        }
    }

    // Create a new day-end expense record
    PetrolDayExpense::create([
        'date' => $request->date,
        'amount' => $request->amount,
        'proof' => json_encode($proofPaths), // Store paths as JSON array
        'type' => 'petrol',
        'is_approved' => false, // Default to not approved
    ]);

    return redirect()->route('day-end-expenses.create')->with('success', 'Day End Expense added successfully!');
}


   // Display all day-end expenses and total income
   public function index()
   {
       $expenses = PetrolDayExpense::all();
       $totalExpense = PetrolDayExpense::where('is_approved', true)->sum('amount');
    //    dd($totalExpense);
       return view('petrolset.expense', compact('expenses', 'totalExpense'));
   }

   public function approveExpense($id)
{
    try {
        $expense = PetrolDayExpense::findOrFail($id); // Find the expense
        $expense->is_approved = true;       // Mark it as approved
        $expense->save();                   // Save the changes

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

public function edit($id)
    {
        $expense = PetrolDayExpense::findOrFail($id); // Retrieve the specific expense

        // Prevent editing if the expense is approved
    if ($expense->is_approved) {
        return redirect()->route('day-end-expenses.index')->with('error', 'You cannot edit an approved expense.');
    }

        return view('petrolset.expenseedit', compact('expense'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'date' => 'required|date',
        'amount' => 'required|numeric',
        'proof' => 'nullable|array',  // Allow proof to be an array
        'proof.*' => 'image|mimes:jpeg,png,jpg', // Each file must be an image
    ]);

    $expense = PetrolDayExpense::findOrFail($id);

    $expense->date = $request->date;
    $expense->amount = $request->amount;

    // Handle multiple file uploads if present
    if ($request->hasFile('proof')) {
        // Delete old proofs if they exist
        if ($expense->proof) {
            $existingProofs = json_decode($expense->proof, true);
            foreach ($existingProofs as $proof) {
                if (\Storage::exists('public/' . $proof)) {
                    \Storage::delete('public/' . $proof);
                }
            }
        }

        // Store new proofs
        $proofPaths = [];
        foreach ($request->file('proof') as $file) {
            $proofPaths[] = $file->store('proofs', 'public');
        }

        // Save the new proofs as JSON
        $expense->proof = json_encode($proofPaths);
    }

    $expense->save();

    return redirect()->route('day-end-expenses.index', $id)
        ->with('success', 'Expense updated successfully!');
}

}
