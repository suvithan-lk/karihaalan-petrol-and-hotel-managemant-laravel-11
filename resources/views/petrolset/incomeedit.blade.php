@extends('layouts.dashboard')
@section('title', 'Edit Day End Income')
@section('content')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="card">
                    <div class="card-inner">
                        <div class="card-head">
                            <h5 class="card-title">Edit Day End Income</h5>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form id="incomeForm" action="{{ route('dayendincome.update', $income->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $income->date) }}" required>
                                @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="amount" class="form-label">Amount:</label>
                                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $income->amount) }}" required >
                                @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="proof" class="form-label">Proof Image:</label>
                                <input type="file" name="proof[]" id="proof" class="form-control @error('proof') is-invalid @enderror" accept="image/*" multiple>
                                @error('proof')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small>Leave blank if you don't want to change the proof image.</small>
                            </div>

                            <button type="submit" id="submitBtn" class="btn btn-primary">Update</button>
                            <div id="uploadStatus" class="text-muted mt-2" style="display: none;">Uploading, please wait...</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('incomeForm').addEventListener('submit', function (e) {
        const submitBtn = document.getElementById('submitBtn');
        const uploadStatus = document.getElementById('uploadStatus');

        // Disable the button and show the uploading status
        submitBtn.disabled = true;
        uploadStatus.style.display = 'block';
    });
</script>

@endsection
