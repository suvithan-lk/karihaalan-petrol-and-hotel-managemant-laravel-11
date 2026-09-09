<?php

namespace App\Http\Controllers;

use App\Models\PetrolDayIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetrolDayIncomeController extends Controller
{
    public function create()
    {
        $incomes = PetrolDayIncome::latest()->get();
        $totalIncome = PetrolDayIncome::where('is_approved', true)->sum('amount');

        return view('petrolset.income', compact('totalIncome', 'incomes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'proof' => ['required', 'array', 'min:1'],
            'proof.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'type' => ['required', 'string', 'max:255'],
        ]);

        $proofPaths = [];
        foreach ($request->file('proof', []) as $file) {
            $proofPaths[] = $file->store('income-proof', 'public');
        }

        PetrolDayIncome::create([
            'date' => $validated['date'],
            'amount' => $validated['amount'],
            'proof' => json_encode($proofPaths),
            'type' => $validated['type'],
            'is_approved' => false,
        ]);

        return redirect()->route('dayendincome.create')->with('success', 'Income added successfully!');
    }

    public function index()
    {
        $incomes = PetrolDayIncome::latest()->get();
        $totalIncome = PetrolDayIncome::where('is_approved', true)->sum('amount');

        return view('petrolset.income', compact('incomes', 'totalIncome'));
    }

    public function approve($id)
    {
        $income = PetrolDayIncome::findOrFail($id);

        if ($income->is_approved) {
            return response()->json(['success' => false, 'message' => 'Income is already approved.'], 422);
        }

        $income->update(['is_approved' => true]);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $income = PetrolDayIncome::findOrFail($id);

        if ($income->is_approved) {
            return redirect()->back()->with('error', 'You cannot edit an approved income.');
        }

        return view('petrolset.incomeedit', compact('income'));
    }

    public function update(Request $request, $id)
    {
        $income = PetrolDayIncome::findOrFail($id);

        if ($income->is_approved) {
            return redirect()->back()->with('error', 'You cannot update an approved income.');
        }

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'proof' => ['nullable', 'array'],
            'proof.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $income->date = $validated['date'];
        $income->amount = $validated['amount'];

        if ($request->hasFile('proof')) {
            foreach (json_decode($income->proof ?: '[]', true) as $proof) {
                Storage::disk('public')->delete($proof);
            }

            $proofPaths = [];
            foreach ($request->file('proof') as $file) {
                $proofPaths[] = $file->store('income-proof', 'public');
            }
            $income->proof = json_encode($proofPaths);
        }

        $income->save();

        return redirect()->route('dayendincome.index')->with('success', 'Day End Income updated successfully!');
    }
}
