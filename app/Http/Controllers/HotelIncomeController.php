<?php

namespace App\Http\Controllers;

use App\Models\HotelIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelIncomeController extends Controller
{
    public function index()
    {
        $incomes = HotelIncome::latest('date')->get();
        $totalIncome = HotelIncome::where('is_approved', true)->sum('amount');
        return view('hotel.income', compact('incomes', 'totalIncome'));
    }

    public function create()
    {
        return $this->index();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'proof' => ['nullable', 'array'],
            'proof.*' => ['image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $paths = [];
        foreach ($request->file('proof', []) as $file) {
            $paths[] = $file->store('proof_images', 'public');
        }

        HotelIncome::create([
            'date' => $validated['date'],
            'amount' => $validated['amount'],
            'proof' => json_encode($paths),
            'is_approved' => false,
        ]);

        return redirect()->route('hotelincome.index')->with('success', 'Hotel income added successfully.');
    }

    public function approveIncome($id)
    {
        $income = HotelIncome::findOrFail($id);
        if ($income->is_approved) {
            return response()->json(['success' => false, 'message' => 'Income is already approved.'], 422);
        }
        $income->update(['is_approved' => true]);
        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $income = HotelIncome::findOrFail($id);
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }
        return view('hotel.incomeedit', compact('income'));
    }

    public function update(Request $request, $id)
    {
        $income = HotelIncome::findOrFail($id);
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }
        if ($income->is_approved) {
            abort(422, 'Approved income cannot be edited.');
        }

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'proof' => ['nullable', 'array'],
            'proof.*' => ['image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $income->date = $validated['date'];
        $income->amount = $validated['amount'];

        if ($request->hasFile('proof')) {
            foreach ((array) json_decode($income->proof, true) as $file) {
                if ($file) Storage::disk('public')->delete($file);
            }
            $paths = [];
            foreach ($request->file('proof', []) as $file) {
                $paths[] = $file->store('proof_images', 'public');
            }
            $income->proof = json_encode($paths);
        }

        $income->save();
        return redirect()->route('hotelincome.index')->with('success', 'Income record updated successfully.');
    }
}
