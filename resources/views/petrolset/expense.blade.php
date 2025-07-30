@extends('layouts.dashboard')
@section('title', 'Day End Expenses List')
@section('content')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="card h-100">
                    <!-- Card Header -->
                    <div class="card-head p-3 border-bottom">
                        <h5 class="card-title mb-0">Day End Expenses</h5>
                    </div>

                    <!-- Card Body -->
                    <div class="card-inner p-4">
                        @if (auth()->user()->role == 'admin')
                            <!-- Expense Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Created At</th>
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
                                                <td>{{ $expense->created_at }}</td>
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
                                                        <button onclick="approveExpense({{ $expense->id }})"
                                                                class="btn btn-success btn-sm">
                                                            Approve
                                                        </button>
                                                        <a href="{{ route('day-end-expenses.edit', ['id' => $expense->id]) }}" class="btn btn-primary">
                                                            Edit
                                                        </a>
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
                                <h5 class="mb-0">Total Expense: {{$totalExpense}}</h5>
                            </div>
                        @endif

                        @if (auth()->user()->role == 'petrol')
                            <!-- Success Message -->
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <!-- Day End Expense Form -->
                            <form action="{{ route('day-end-expenses.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="card-head p-3 border-bottom">
                                    <h5 class="card-title mb-0">Day End Expense Form</h5>
                                </div>

                                <div class="form-group mt-3">
                                    <label for="date" class="form-label">Date:</label>
                                    <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
                                    @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="form-group mt-3">
                                    <label for="amount" class="form-label">Amount:</label>
                                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="form-group mt-3">
                                    <label for="proof" class="form-label">Proof (Optional):</label>
                                    <input type="file" name="proof[]" id="proof" class="form-control @error('proof') is-invalid @enderror" accept="image/*" multiple>
                                    @error('proof')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <button type="submit" class="btn btn-primary mt-4">Add Expense</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for viewing proof image -->
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
    // Handle form submission
    document.getElementById('dayEndIncomeForm').addEventListener('submit', function (event) {
        const submitButton = document.getElementById('submitButton');
        const uploadingStatus = document.getElementById('uploadingStatus');

        submitButton.disabled = true; // Disable the submit button
        uploadingStatus.style.display = 'block'; // Show uploading message
    });

    function approveExpense(expenseId) {
        fetch(`/admin/approve-petrol-expense/${expenseId}`, {
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
