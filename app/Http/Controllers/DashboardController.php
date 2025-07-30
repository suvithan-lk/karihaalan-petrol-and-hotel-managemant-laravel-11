<?php

namespace App\Http\Controllers;

use App\Models\PetrolDayIncome;
use App\Models\PetrolDayExpense;
use App\Models\MeterReading;
use App\Models\HotelIncome;
use App\Models\HotelExpense;
use App\Models\RoomBooking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Petrol Pending Counts
        $pendingPetrolIncomeCount = PetrolDayIncome::where('is_approved', false)->count();
        $pendingPetrolExpenseCount = PetrolDayExpense::where('is_approved', false)->count();
        $pendingMeterReadingCount = MeterReading::where('is_approved', false)->count();

        // Hotel Pending Counts
        $pendingHotelIncomeCount = HotelIncome::where('is_approved', false)->count();
        $pendingHotelExpenseCount = HotelExpense::where('is_approved', false)->count();
        $pendingRoomBookingCount = RoomBooking::where('is_approved', false)->count();

        // Initialize the variables for income, expense, and profit
        $totalPetrolIncome = 0;
        $totalPetrolExpense = 0;
        $petrolProfit = 0;

        $totalHotelIncome = 0;
        $totalHotelExpense = 0;
        $hotelProfit = 0;

        // Check if start and end date are provided
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : null;

        // Logic for Petrol role
        if (auth()->user()->role == 'admin' || auth()->user()->role == 'petrol') {
            $petrolQuery = PetrolDayIncome::query();
            $petrolExpenseQuery = PetrolDayExpense::query();
            $petrolExpenseQuery->where('is_approved', true);
            $petrolQuery->where('is_approved', true);

            // Apply date range filter if provided
            if ($startDate && $endDate) {
                $petrolQuery->whereBetween('date', [$startDate, $endDate]);
                $petrolExpenseQuery->whereBetween('date', [$startDate, $endDate]);
            }

            // Calculate total petrol income and expense
            $totalPetrolIncome = $petrolQuery->sum('amount');
            $totalPetrolExpense = $petrolExpenseQuery->sum('amount');
            $petrolProfit = $totalPetrolIncome - $totalPetrolExpense;
        }

        // Logic for Hotel role
        if (auth()->user()->role == 'admin' || auth()->user()->role == 'hotel') {
            $hotelQuery = HotelIncome::query();
            $hotelExpenseQuery = HotelExpense::query();
            $hotelExpenseQuery->where('is_approved', true);
            $hotelQuery->where('is_approved', true);

            // Apply date range filter if provided
            if ($startDate && $endDate) {
                $hotelQuery->whereBetween('date', [$startDate, $endDate]);
                $hotelExpenseQuery->whereBetween('date', [$startDate, $endDate]);
            }

            // Calculate total hotel income and expense
            $totalHotelIncome = $hotelQuery->sum('amount');
            $totalHotelExpense = $hotelExpenseQuery->sum('amount');
            $hotelProfit = $totalHotelIncome - $totalHotelExpense;
        }

        // Return the view with the computed values
        return view('index', compact(
            'totalPetrolIncome', 'totalPetrolExpense', 'petrolProfit',
            'totalHotelIncome', 'totalHotelExpense', 'hotelProfit' , 'pendingPetrolIncomeCount',
            'pendingPetrolExpenseCount',
            'pendingMeterReadingCount',
            'pendingHotelIncomeCount',
            'pendingHotelExpenseCount',
            'pendingRoomBookingCount',
        ));
    }


    public function count()
    {


        return view('index', compact([


        ]));
    }
}
