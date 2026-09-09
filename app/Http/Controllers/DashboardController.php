<?php

namespace App\Http\Controllers;

use App\Models\PetrolDayIncome;
use App\Models\PetrolDayExpense;
use App\Models\MeterReading;
use App\Models\HotelIncome;
use App\Models\HotelExpense;
use App\Models\RoomBooking;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $role = $user->role;

        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $startDate = isset($validated['start_date'])
            ? Carbon::parse($validated['start_date'])->startOfDay()
            : null;
        $endDate = isset($validated['end_date'])
            ? Carbon::parse($validated['end_date'])->endOfDay()
            : null;

        $pendingPetrolIncomeCount = 0;
        $pendingPetrolExpenseCount = 0;
        $pendingMeterReadingCount = 0;
        $pendingHotelIncomeCount = 0;
        $pendingHotelExpenseCount = 0;
        $pendingRoomBookingCount = 0;

        $totalPetrolIncome = 0;
        $totalPetrolExpense = 0;
        $petrolProfit = 0;
        $totalHotelIncome = 0;
        $totalHotelExpense = 0;
        $hotelProfit = 0;

        if (in_array($role, ['admin', 'petrol'], true)) {
            $pendingPetrolIncomeCount = PetrolDayIncome::where('is_approved', false)->count();
            $pendingPetrolExpenseCount = PetrolDayExpense::where('is_approved', false)->count();
            $pendingMeterReadingCount = MeterReading::where('is_approved', false)->count();

            $incomeQuery = PetrolDayIncome::where('is_approved', true);
            $expenseQuery = PetrolDayExpense::where('is_approved', true);

            if ($startDate && $endDate) {
                $incomeQuery->whereBetween('date', [$startDate, $endDate]);
                $expenseQuery->whereBetween('date', [$startDate, $endDate]);
            }

            $totalPetrolIncome = $incomeQuery->sum('amount');
            $totalPetrolExpense = $expenseQuery->sum('amount');
            $petrolProfit = $totalPetrolIncome - $totalPetrolExpense;
        }

        if (in_array($role, ['admin', 'hotel'], true)) {
            $pendingHotelIncomeCount = HotelIncome::where('is_approved', false)->count();
            $pendingHotelExpenseCount = HotelExpense::where('is_approved', false)->count();
            $pendingRoomBookingCount = RoomBooking::where('is_approved', false)->count();

            $incomeQuery = HotelIncome::where('is_approved', true);
            $expenseQuery = HotelExpense::where('is_approved', true);

            if ($startDate && $endDate) {
                $incomeQuery->whereBetween('date', [$startDate, $endDate]);
                $expenseQuery->whereBetween('date', [$startDate, $endDate]);
            }

            $totalHotelIncome = $incomeQuery->sum('amount');
            $totalHotelExpense = $expenseQuery->sum('amount');
            $hotelProfit = $totalHotelIncome - $totalHotelExpense;
        }

        return view('index', compact(
            'totalPetrolIncome', 'totalPetrolExpense', 'petrolProfit',
            'totalHotelIncome', 'totalHotelExpense', 'hotelProfit',
            'pendingPetrolIncomeCount', 'pendingPetrolExpenseCount',
            'pendingMeterReadingCount', 'pendingHotelIncomeCount',
            'pendingHotelExpenseCount', 'pendingRoomBookingCount'
        ));
    }
}
