<?php

namespace App\Http\Controllers;

use App\Models\HotelExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelExpenseController extends Controller
{
    public function create()
    {
        $expenses = HotelExpense::latest('date')->get();
        $totalExpense = HotelExpense::where('is_approved', true)->sum('amount');
        return view('hotel.expense', compact('expenses', 'totalExpense'));
    }

    public function index()
    {
        return $this->create();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'proof' => ['required', 'array', 'min:1'],
            'proof.*' => ['image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $paths = [];
        foreach ($request->file('proof', []) as $file) {
            $paths[] = $file->store('proof_images', 'public');
        }

        HotelExpense::create([
            'date' => $validated['date'],
            'amount' => $validated['amount'],
            'proof' => json_encode($paths),
            'is_approved' => false,
        ]);

        return redirect()->route('hotel-expenses.index')->with('success', 'Hotel expense added successfully.');
    }

    public function approveExpense($id)
    {
        $expense = HotelExpense::findOrFail($id);
        if ($expense->is_approved) {
            return response()->json(['success' => false, 'message' => 'Expense is already approved.'], 422);
        }
        $expense->update(['is_approved' => true]);
        return response()->json(['success' => true]);
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
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }
        if ($expense->is_approved) {
            abort(422, 'Approved expense cannot be edited.');
        }

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'proof' => ['nullable', 'array'],
            'proof.*' => ['image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $expense->date = $validated['date'];
        $expense->amount = $validated['amount'];

        if ($request->hasFile('proof')) {
            foreach ((array) json_decode($expense->proof, true) as $file) {
                if ($file) Storage::disk('public')->delete($file);
            }
            $paths = [];
            foreach ($request->file('proof', []) as $file) {
                $paths[] = $file->store('proof_images', 'public');
            }
            $expense->proof = json_encode($paths);
        }

        $expense->save();
        return redirect()->route('hotel-expenses.index')->with('success', 'Expense record updated successfully.');
    }
}
