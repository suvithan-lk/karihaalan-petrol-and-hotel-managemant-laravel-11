@extends('layouts.dashboard')
@section('title', 'Petrol Meter Reading Form')
@section('content')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="card h-100">
                    <div class="card-inner">
                        <div class="card-head">
                            <h5 class="card-title">Petrol Meter Reading</h5>
                        </div>
                        {{-- Success Message --}}
                        @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif
                        <form id="petrol-meter-form" action="{{route('petrolmeter.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="meter_reading" class="form-label">Meter Reading (liters of fuel):</label>
                                <div class="form-control-wrap">
                                    <input type="number" step="0.01" name="meter_reading" id="meter_reading" class="form-control @if ($errors->has('meter_reading')){{'is-invalid'}} @endif" placeholder="Enter meter reading" required>
                                    @if ($errors->has('meter_reading'))
                                    <div class="invalid-feedback">{{$errors->first('meter_reading')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="amount_received" class="form-label">Amount Received:</label>
                                <div class="form-control-wrap">
                                    <input type="number" step="0.01" name="amount_received" id="amount_received" class="form-control @if ($errors->has('amount_received')){{'is-invalid'}} @endif" placeholder="Enter amount received" required>
                                    @if ($errors->has('meter_reading'))
                                    <div class="invalid-feedback">{{$errors->first('amount_received')}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="proof_image" class="form-label">Proof Image (Meter Reading Account):</label>
                                <div class="form-control-wrap">
                                    <input type="file" name="proof_image[]" id="proof_image" class="form-control @if ($errors->has('proof_image')){{'is-invalid'}} @endif" accept="image/*" required multiple>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" id="submit-button" class="btn btn-success">Save Informations</button>
                                <div id="uploading-status" class="mt-2 text-info" style="display: none;">Uploading...</div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('petrol-meter-form').addEventListener('submit', function (event) {
        const submitButton = document.getElementById('submit-button');
        const uploadingStatus = document.getElementById('uploading-status');

        // Disable the submit button
        submitButton.disabled = true;
        submitButton.innerHTML = 'Submitting...';

        // Show the uploading status
        uploadingStatus.style.display = 'block';
    });
</script>

@endsection
