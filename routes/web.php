<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HotelIncomeController;
use App\Http\Controllers\HotelRoomController;
use App\Http\Controllers\PetrolDayExpenseController;
use App\Http\Controllers\PetrolDayIncomeController;
use App\Http\Controllers\PetrolMeterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfitController;
use App\Http\Controllers\RoomBookingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelExpenseController;

Route::resource('users', UserController::class)->middleware(['auth', 'role:admin']);

Route::get('/', [AuthController::class, 'showLogin'])->middleware('is_login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('auth.logout');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/petrol-profits', [ProfitController::class, 'index'])->name('petrolprofits');

    Route::get('/petrolmeter', [PetrolMeterController::class, 'index'])->name('petrolmeter')->middleware('role:admin,petrol');
    Route::get('/petrolmeter/form', [PetrolMeterController::class, 'show'])->name('petrolmeter.form')->middleware('role:admin');
    Route::post('/petrolmeter/store', [PetrolMeterController::class, 'store'])->name('petrolmeter.store')->middleware('role:petrol');
    Route::post('/admin/approve-meter/{id}', [PetrolMeterController::class, 'approveMeter'])->name('approve.meter')->middleware('role:admin,petrol');
    Route::get('/petrolmeter/{id}/edit', [PetrolMeterController::class, 'edit'])->name('petrolmeter.edit')->middleware('role:admin,petrol');
    Route::put('/petrolmeter/{id}', [PetrolMeterController::class, 'update'])->name('petrolmeter.update')->middleware('role:admin,petrol');

    Route::get('/contact-add', [ContactController::class, 'create'])->name('contact.create');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    Route::get('/contact-view', [ContactController::class, 'show'])->name('contact.view');

    Route::get('/dayendincome', [PetrolDayIncomeController::class, 'create'])->name('dayendincome.create')->middleware('role:petrol,admin');
    Route::post('/dayendincome2', [PetrolDayIncomeController::class, 'store'])->name('dayendincome.store')->middleware('role:petrol,admin');
    Route::get('/dayendincome/list', [PetrolDayIncomeController::class, 'index'])->name('dayendincome.index')->middleware('role:admin,petrol');
    Route::post('/admin/approve-petrol-income/{id}', [PetrolDayIncomeController::class, 'approve'])->name('income.approve')->middleware('role:admin');
    Route::get('/dayendincome/{income}/edit', [PetrolDayIncomeController::class, 'edit'])->name('dayendincome.edit')->middleware('role:petrol,admin');
    Route::put('/dayendincome/{income}', [PetrolDayIncomeController::class, 'update'])->name('dayendincome.update')->middleware('role:petrol,admin');

    Route::get('/day-end-expenses', [PetrolDayExpenseController::class, 'create'])->name('day-end-expenses.create')->middleware('role:petrol,admin');
    Route::post('/day-end-expenses', [PetrolDayExpenseController::class, 'store'])->name('day-end-expenses.store')->middleware('role:petrol,admin');
    Route::get('/day-end-expenses/list', [PetrolDayExpenseController::class, 'index'])->name('day-end-expenses.index')->middleware('role:admin');
    Route::post('/admin/approve-petrol-expense/{id}', [PetrolDayExpenseController::class, 'approveExpense'])->name('approve.expense')->middleware('role:admin');
    Route::get('/day-end-expenses/{id}/edit', [PetrolDayExpenseController::class, 'edit'])->name('day-end-expenses.edit')->middleware('role:petrol,admin');
    Route::put('/day-end-expenses/{id}', [PetrolDayExpenseController::class, 'update'])->name('day-end-expenses.update')->middleware('role:petrol,admin');

    Route::middleware('role:admin')->group(function () {
        Route::resource('hotelrooms', HotelRoomController::class)->except(['show', 'destroy']);
    });

    Route::middleware('role:hotel,admin')->group(function () {
        Route::get('roombookings/create', [RoomBookingController::class, 'index'])->name('roombookings.create');
        Route::post('roombookings/store', [RoomBookingController::class, 'store'])->name('roombookings.store');
        Route::get('/hotelincome', [HotelIncomeController::class, 'create'])->name('hotelincome.create');
        Route::post('/hotelincome', [HotelIncomeController::class, 'store'])->name('hotelincome.store');
        Route::get('/hotelincome/list', [HotelIncomeController::class, 'index'])->name('hotelincome.index');
        Route::get('/hotelincome/{id}/edit', [HotelIncomeController::class, 'edit'])->name('hotelincome.edit');
        Route::put('/hotelincome/{id}', [HotelIncomeController::class, 'update'])->name('hotelincome.update');
        Route::get('/hotels/expense/create', [HotelExpenseController::class, 'create'])->name('hotel-expenses.create');
        Route::post('/hotels/expense', [HotelExpenseController::class, 'store'])->name('hotel-expenses.store');
        Route::get('/hotelExpense/{id}/edit', [HotelExpenseController::class, 'edit'])->name('hotelExpense.edit');
        Route::put('/hotelExpense/{id}', [HotelExpenseController::class, 'update'])->name('hotelExpense.update');
        Route::get('/bookings/{id}/edit', [RoomBookingController::class, 'edit'])->name('bookings.edit');
        Route::put('/bookings/{id}', [RoomBookingController::class, 'update'])->name('bookings.update');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/view-bookings', [RoomBookingController::class, 'show'])->name('roombookings.show');
        Route::post('/approve-booking/{id}', [RoomBookingController::class, 'approve'])->name('booking.approve');
        Route::get('/hotel-expenses/list', [HotelExpenseController::class, 'index'])->name('hotel-expenses.index');
        Route::post('/admin/approve-income/{id}', [HotelIncomeController::class, 'approveIncome'])->name('approve.income');
        Route::post('/admin/approve-expense/{id}', [HotelExpenseController::class, 'approveExpense'])->name('approve.expense');
    });
});
