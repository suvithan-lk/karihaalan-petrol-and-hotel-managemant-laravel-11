@extends('layouts.dashboard')
@section('title', 'Hotel Expense')
@section('content')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="card">
                    <div class="card-inner">
                        @if (auth()->user()->role == 'hotel')
                            <!-- Card Header -->
                            <div class="card-head p-3 border-bottom">
                                <h5 class="card-title mb-0">Add Hotel Expense</h5>
                            </div>

                            <!-- Success Message -->
                            @if(session('success'))
                                <div class="alert alert-success mt-3">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <!-- Hotel Expense Form -->
                            <form action="{{ route('hotel-expenses.store') }}" method="POST" enctype="multipart/form-data" class="mt-4" id="expenseForm">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="date" class="form-label">Date:</label>
                                    <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
                                    @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="amount" class="form-label">Amount:</label>
                                    <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label for="proof" class="form-label">Proof Image (Optional):</label>
                                    <input type="file" name="proof[]" id="proof" class="form-control @error('proof') is-invalid @enderror" accept="image/*" multiple>
                                    @error('proof')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <!-- Uploading status -->
                                <div id="uploadingStatus" style="display: none;">
                                    <p>Uploading your image... Please wait.</p>
                                </div>

                                <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                            </form>
                        @endif

                        @if (auth()->user()->role == 'admin')
                            <!-- Admin View: Hotel Expense Records -->
                            <div class="card-head p-3 border-bottom mt-5">
                                <h5 class="card-title mb-0">Hotel Expense Records</h5>
                            </div>

                            <div class="table-responsive mt-4">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Proof</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($expenses as $expense)
                                            <tr>
                                                <td>{{ $expense->id }}</td>
                                                <td>{{ $expense->date }}</td>
                                                <td>{{ $expense->amount }}</td>
                                                <td>
                                                    <button class="btn btn-primary" style="cursor: pointer;" onclick="openImageModal({{$expense->proof}})">
                                                        View Proof
                                                    </button>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $expense->is_approved ? 'bg-success' : 'bg-warning' }}">
                                                        {{ $expense->is_approved ? 'Approved' : 'Pending' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if(!$expense->is_approved)
                                                        <button onclick="approveExpense({{ $expense->id }})" class="btn btn-success btn-sm">Approve</button>
                                                        <a href="{{ route('hotelExpense.edit', ['id' => $expense->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                                                    @else
                                                        <button class="btn btn-dark btn-sm" disabled>Approved</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Card Footer -->
                            <div class="card-footer border-top p-3">
                                <h5 class="mb-0">Total Expense: {{ $totalExpense }}</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for viewing proof image -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Proof Images</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="proofImagesContainer">
                <!-- Images will be dynamically added here -->
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('expenseForm').addEventListener('submit', function(e) {
        // Disable the submit button
        document.getElementById('submitBtn').disabled = true;
        // Show the uploading status message
        document.getElementById('uploadingStatus').style.display = 'block';
    });

    function approveExpense(expenseId) {
        fetch(`/admin/approve-expense/${expenseId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Expense approved successfully!');
                location.reload(); // Reload the page to reflect the updated status
            } else {
                alert(`Error: ${data.message || 'Could not approve expense'}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred. Please try again.');
        });
    }
        // Open the image modal
    function openImageModal(imageUrls) {

        if (typeof imageUrls === 'string') {
        imageUrls = JSON.parse(imageUrls);
        }
        // Clear any existing images in the modal body
        let container = document.getElementById('proofImagesContainer');
        container.innerHTML = '';  // Clear previous images

        // Loop through the image URLs and add them to the modal
        imageUrls.forEach(url => {
        let imgElement = document.createElement('img');

        // Generate the full image URL using the asset function
        imgElement.src = '/storage/' + url; // Adjust this if your URL structure is different

        imgElement.alt = 'Proof Image';
        imgElement.classList.add('img-fluid', 'mb-2');  // Add Bootstrap classes for responsive images
        container.appendChild(imgElement);
        });

        // Show the modal
        $('#imageModal').modal('show');
    }
</script>

@endsection
