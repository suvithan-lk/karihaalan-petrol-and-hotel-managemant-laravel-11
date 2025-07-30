<?php

namespace App\Http\Controllers;
use App\Http\Controllers\PetrolDayIncomeController;
use App\Http\Requests\PetrolDayIncomeRequest;
use App\Models\PetrolDayExpense;
use App\Models\PetrolDayIncome;
use Illuminate\Http\Request;

class ProfitController extends Controller
{

    public function index(){
    {
        // Calculate total income
        $totalIncome = PetrolDayIncome::sum('amount');

        // Calculate total expense
        $totalExpense = PetrolDayExpense::sum('amount');

        // Calculate profit
        $profit = $totalIncome - $totalExpense;

        // Return view with data
        return view('petrolset.profits', compact('totalIncome', 'totalExpense', 'profit'));
        // return view("petrolset.profits");
    }

}
}
