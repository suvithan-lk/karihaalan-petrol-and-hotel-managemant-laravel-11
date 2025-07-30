@extends('layouts.dashboard')
@section('title', 'Edit Hotel Income')
@section('content')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="card">
                    <div class="card-inner">
                        <div class="card-head">
                            <h5 class="card-title">Edit Hotel Income</h5>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form id="income-form" action="{{ route('hotelincome.update', $income->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $income->date) }}" required>
                                @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="amount" class="form-label">Amount:</label>
                                <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $income->amount) }}" required>
                                @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="proof" class="form-label">Proof Image (Optional):</label>
                                <input type="file" name="proof[]" id="proof" class="form-control @error('proof') is-invalid @enderror" accept="image/*" multiple>
                                @error('proof')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" id="submit-button" class="btn btn-primary">Update</button>
                            <span id="uploading-status" class="text-info d-none">Uploading...</span>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('income-form');
        const submitButton = document.getElementById('submit-button');
        const uploadingStatus = document.getElementById('uploading-status');

        form.addEventListener('submit', function () {
            // Disable the submit button
            submitButton.disabled = true;

            // Show the "Uploading..." status
            uploadingStatus.classList.remove('d-none');

            // Optional: Change the button text to show progress
            submitButton.textContent = 'Updating...';
        });
    });
</script>

@endsection
