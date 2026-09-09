<?php

namespace App\Http\Controllers;

use App\Models\PetrolDayExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetrolDayExpenseController extends Controller
{
    public function create()
    {
        $expenses = PetrolDayExpense::latest()->get();
        $totalExpense = PetrolDayExpense::where('is_approved', true)->sum('amount');

        return view('petrolset.expense', compact('totalExpense', 'expenses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'proof' => ['nullable', 'array'],
            'proof.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $proofPaths = [];
        foreach ($request->file('proof', []) as $file) {
            $proofPaths[] = $file->store('expense-proof', 'public');
        }

        PetrolDayExpense::create([
            'date' => $validated['date'],
            'amount' => $validated['amount'],
            'proof' => json_encode($proofPaths),
            'type' => 'petrol',
            'is_approved' => false,
        ]);

        return redirect()->route('day-end-expenses.create')->with('success', 'Day End Expense added successfully!');
    }

    public function index()
    {
        $expenses = PetrolDayExpense::latest()->get();
        $totalExpense = PetrolDayExpense::where('is_approved', true)->sum('amount');

        return view('petrolset.expense', compact('expenses', 'totalExpense'));
    }

    public function approveExpense($id)
    {
        $expense = PetrolDayExpense::findOrFail($id);

        if ($expense->is_approved) {
            return response()->json(['success' => false, 'message' => 'Expense is already approved.'], 422);
        }

        $expense->update(['is_approved' => true]);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $expense = PetrolDayExpense::findOrFail($id);

        if ($expense->is_approved) {
            return redirect()->route('day-end-expenses.index')->with('error', 'You cannot edit an approved expense.');
        }

        return view('petrolset.expenseedit', compact('expense'));
    }

    public function update(Request $request, $id)
    {
        $expense = PetrolDayExpense::findOrFail($id);

        if ($expense->is_approved) {
            return redirect()->back()->with('error', 'You cannot update an approved expense.');
        }

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'proof' => ['nullable', 'array'],
            'proof.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $expense->date = $validated['date'];
        $expense->amount = $validated['amount'];

        if ($request->hasFile('proof')) {
            foreach (json_decode($expense->proof ?: '[]', true) as $proof) {
                Storage::disk('public')->delete($proof);
            }

            $proofPaths = [];
            foreach ($request->file('proof') as $file) {
                $proofPaths[] = $file->store('expense-proof', 'public');
            }
            $expense->proof = json_encode($proofPaths);
        }

        $expense->save();

        return redirect()->route('day-end-expenses.index')->with('success', 'Expense updated successfully!');
    }
}
