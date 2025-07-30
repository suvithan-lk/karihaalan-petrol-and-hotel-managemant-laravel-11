@extends('layouts.dashboard')
@section('title', 'Edit Day End Expense')
@section('content')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="card h-100">
                    <!-- Card Header -->
                    <div class="card-head p-3 border-bottom">
                        <h5 class="card-title mb-0">Edit Day End Expense</h5>
                    </div>

                    <!-- Card Body -->
                    <div class="card-inner p-4">
                        <!-- Display success or error messages -->
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Edit Expense Form -->
                        <form id="editExpenseForm" action="{{ route('day-end-expenses.update', $expense->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT') <!-- For updating resource -->

                            <div class="form-group mt-3">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $expense->date) }}" required>
                                @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group mt-3">
                                <label for="amount" class="form-label">Amount:</label>
                                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $expense->amount) }}" required>
                                @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group mt-3">
                                <label for="proof" class="form-label">Proof (Optional):</label>
                                <input type="file" name="proof[]" id="proof" class="form-control @error('proof') is-invalid @enderror" accept="image/*" multiple>
                                @if($expense->proof)
                                    <small class="form-text text-muted">Current proof: <a href="{{ asset('storage/' . $expense->proof) }}" target="_blank">View</a></small>
                                @endif
                                @error('proof')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Status Message -->
                            <div id="uploadStatus" class="mt-3 text-success" style="display: none;">
                                Uploading, please wait...
                            </div>

                            <button type="submit" id="submitButton" class="btn btn-primary mt-4">Update Expense</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    document.getElementById('editExpenseForm').addEventListener('submit', function(event) {
        // Disable the submit button
        const submitButton = document.getElementById('submitButton');
        submitButton.disabled = true;

        // Show the upload status
        const uploadStatus = document.getElementById('uploadStatus');
        uploadStatus.style.display = 'block';

        // Optional: Change the button text while submitting
        submitButton.textContent = 'Uploading...';
    });
</script>

@endsection
