@extends('layouts.dashboard')

@section('title', 'Edit Hotel Expense')

@section('content')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="card">
                    <div class="card-inner">
                        <div class="card-head">
                            <h5 class="card-title">Edit Hotel Expense</h5>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form id="expenseForm" action="{{ route('hotelExpense.update', ['id' => $expense->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $expense->date) }}" required>
                                @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="amount" class="form-label">Amount:</label>
                                <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $expense->amount) }}" required>
                                @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="proof" class="form-label">Proof Image (Optional):</label>
                                <input type="file" name="proof[]" id="proof" class="form-control @error('proof') is-invalid @enderror" accept="image/*" multiple>
                                @error('proof')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" id="submitBtn" class="btn btn-primary">Save Changes</button>
                                <a href="{{ route('hotel-expenses.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>

                        <!-- Uploading status -->
                        <div id="uploadingStatus" class="d-none">
                            <p>Uploading image...</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('expenseForm').addEventListener('submit', function(e) {
        // Disable submit button and show uploading status
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('uploadingStatus').classList.remove('d-none');
    });

    function approveExpense(id) {
        fetch(`/hotelExpense/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Expense approved successfully!');
                location.reload();
            } else {
                alert('Failed to approve expense: ' + data.message);
            }
        })
        .catch(error => {
            alert('An error occurred: ' + error.message);
        });
    }
</script>

@endsection
