@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">


                    <!-- Pending Approvals Section -->
                    <div class="row g-4 mb-4">
                        <h4 class="col-12 mb-3 text-center">Pending Approvals</h4>

                        @if (auth()->user()->role =='admin' || auth()->user()->role =='petrol')
                        <!-- Petrol Pending Counts -->
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="card shadow-lg border-0">
                                <div class="card-body bg-danger text-white rounded">
                                    <h5 class="card-title">Petrol Income Pending</h5>
                                    <p class="card-text">{{ $pendingPetrolIncomeCount }} Pending</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="card shadow-lg border-0">
                                <div class="card-body bg-warning text-dark rounded">
                                    <h5 class="card-title">Petrol Expense Pending</h5>
                                    <p class="card-text">{{ $pendingPetrolExpenseCount }} Pending</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="card shadow-lg border-0">
                                <div class="card-body bg-info text-white rounded">
                                    <h5 class="card-title">Meter Reading Pending</h5>
                                    <p class="card-text">{{ $pendingMeterReadingCount }} Pending</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if (auth()->user()->role =='admin' || auth()->user()->role =='hotel')

                        <!-- Hotel Pending Counts -->
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="card shadow-lg border-0">
                                <div class="card-body bg-primary text-white rounded">
                                    <h5 class="card-title">Hotel Income Pending</h5>
                                    <p class="card-text">{{ $pendingHotelIncomeCount }} Pending</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="card shadow-lg border-0">
                                <div class="card-body bg-success text-white rounded">
                                    <h5 class="card-title">Hotel Expense Pending</h5>
                                    <p class="card-text">{{ $pendingHotelExpenseCount }} Pending</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="card shadow-lg border-0">
                                <div class="card-body bg-dark text-white rounded">
                                    <h5 class="card-title">Room Booking Pending</h5>
                                    <p class="card-text">{{ $pendingRoomBookingCount }} Pending</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Date Range Filter Form -->
                <form method="GET" action="{{ route('dashboard') }}" class="mb-5">
                    <div class="row g-3">
                        <div class="col-md-5 col-12">
                            <input type="date" name="start_date" class="form-control shadow-sm" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-5 col-12">
                            <input type="date" name="end_date" class="form-control shadow-sm" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2 col-12">
                            <button type="submit" class="btn btn-primary w-100 shadow-sm">Filter</button>
                        </div>
                    </div>
                </form>

                <div class="container mt-5">
                    @if (auth()->user()->role == 'admin' || auth()->user()->role == 'petrol')
                        <!-- Petrol Profit Section -->
                        <h2 class="text-center mb-4">Petrol Set Profit</h2>

                        <!-- Petrol Profit Summary Cards -->
                        <div class="row g-4">
                            <div class="col-md-4 col-sm-6 col-12">
                                <div class="card shadow-lg border-0">
                                    <div class="card-body bg-primary text-white rounded">
                                        <h5 class="card-title">Total Income</h5>
                                        <p class="card-text">(Rs): {{ number_format($totalPetrolIncome) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <div class="card shadow-lg border-0">
                                    <div class="card-body bg-success text-white rounded">
                                        <h5 class="card-title">Total Expense</h5>
                                        <p class="card-text">(Rs): {{ number_format($totalPetrolExpense) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <div class="card shadow-lg border-0">
                                    <div class="card-body bg-warning text-dark rounded">
                                        <h5 class="card-title">Profit</h5>
                                        <p class="card-text">(Rs): {{ number_format($petrolProfit) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (auth()->user()->role == 'admin' || auth()->user()->role == 'hotel')
                        <!-- Hotel Profit Section -->
                        <h2 class="text-center mb-4 mt-5">Hotel Profit</h2>

                        <!-- Hotel Profit Summary Cards -->
                        <div class="row g-4">
                            <div class="col-md-4 col-sm-6 col-12">
                                <div class="card shadow-lg border-0">
                                    <div class="card-body bg-primary text-white rounded">
                                        <h5 class="card-title">Total Income</h5>
                                        <p class="card-text">(Rs): {{ number_format($totalHotelIncome) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <div class="card shadow-lg border-0">
                                    <div class="card-body bg-success text-white rounded">
                                        <h5 class="card-title">Total Expense</h5>
                                        <p class="card-text">(Rs): {{ number_format($totalHotelExpense) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <div class="card shadow-lg border-0">
                                    <div class="card-body bg-warning text-dark rounded">
                                        <h5 class="card-title">Profit</h5>
                                        <p class="card-text">(Rs): {{ number_format($hotelProfit) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </div>
</div>

@endsection
