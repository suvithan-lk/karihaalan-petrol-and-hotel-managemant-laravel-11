@extends('layouts.dashboard')
@section('title', 'Edit Petrol Meter Reading')
@section('content')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="card h-100">
                    <div class="card-inner">
                        <div class="card-head">
                            <h5 class="card-title">Edit Petrol Meter Reading</h5>
                        </div>
                        {{-- Success Message --}}
                        @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif
                        <form id="meterReadingForm" action="{{ route('petrolmeter.update', $meterReading->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="meter_reading" class="form-label">Meter Reading (liters of fuel):</label>
                                <div class="form-control-wrap">
                                    <input type="number" step="0.01" name="meter_reading" id="meter_reading" class="form-control @if ($errors->has('meter_reading')){{'is-invalid'}} @endif" value="{{ old('meter_reading', $meterReading->meter_reading) }}" placeholder="Enter meter reading" required>
                                    @if ($errors->has('meter_reading'))
                                    <div class="invalid-feedback">{{$errors->first('meter_reading')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="amount_received" class="form-label">Amount Received:</label>
                                <div class="form-control-wrap">
                                    <input type="number" step="0.01" name="amount_received" id="amount_received" class="form-control @if ($errors->has('amount_received')){{'is-invalid'}} @endif" value="{{ old('amount_received', $meterReading->amount_received) }}" placeholder="Enter amount received" required>
                                    @if ($errors->has('amount_received'))
                                    <div class="invalid-feedback">{{$errors->first('amount_received')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="proof_image" class="form-label">Proof Image (Optional):</label>
                                <div class="form-control-wrap">
                                    <input type="file" name="proof_image[]" id="proof_image" class="form-control @if ($errors->has('proof_image')){{'is-invalid'}} @endif" accept="image/*" multiple>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" id="submitBtn" class="btn btn-primary" data-loading-text="Uploading...">Update Information</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('meterReadingForm');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function () {
            submitBtn.disabled = true; // Disable the button
            submitBtn.innerHTML = submitBtn.getAttribute('data-loading-text'); // Change button text
        });
    });
</script>

@endsection
