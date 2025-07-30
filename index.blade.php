@extends('layouts.app')

@section('main')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>Petrol Meter Reading</h3>
        </div>
        <div class="card-body">
            {{-- {{ route('petrol.meter.store') }} --}}
            <form action="{{route('Store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Meter Reading -->
                <div class="mb-3">
                    <label for="meter_reading" class="form-label">Meter Reading (liters of fuel):</label>
                    <input type="number" step="0.01" name="meter_reading" id="meter_reading" class="form-control @if ($errors->has('meter_reading')){{'is-invalid'}} @endif" placeholder="Enter meter reading" required>
                    @if ($errors->has('meter_reading'))
                    <div class="invalid-feedback">{{$errors->first('meter_reading')}}</div>
                    @endif
                </div>

                <!-- Amount Received -->
                <div class="mb-3">
                    <label for="amount_received" class="form-label">Amount Received:</label>
                    <input type="number" step="0.01" name="amount_received" id="amount_received" class="form-control @if ($errors->has('amount_received')){{'is-invalid'}} @endif" placeholder="Enter amount received" required>
                    @if ($errors->has('meter_reading'))
                    <div class="invalid-feedback">{{$errors->first('amount_received')}}</div>
                    @endif
                </div>

                <!-- Proof Image -->
                <div class="mb-3">
                    <label for="proof_image" class="form-label">Proof Image (Meter Reading Account):</label>
                    <input type="file" name="proof_image" id="proof_image" class="form-control @if ($errors->has('proof_image')){{'is-invalid'}} @endif" accept="image/*" required>
                </div>

                <!-- Submit Button -->
                <div class="text-end">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection