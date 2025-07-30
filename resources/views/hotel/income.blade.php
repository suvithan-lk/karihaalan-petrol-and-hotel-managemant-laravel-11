@extends('layouts.dashboard')
@section('title', 'Hotel Income')
@section('content')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="card">
                    <div class="card-inner">

                        @if (auth()->user()->role == 'hotel')

                        <div class="card-head">
                            <h5 class="card-title">Add Hotel Income</h5>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form id="incomeForm" action="{{ route('hotelincome.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
                                @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="amount" class="form-label">Amount:</label>
                                <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                                @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="proof" class="form-label">Proof Image (Optional):</label>
                                <input type="file" name="proof[]" id="proof" class="form-control @error('proof') is-invalid @enderror" accept="image/*" multiple>
                                @error('proof')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
                            <div id="uploadStatus" style="display:none; margin-top: 10px;" class="text-success">Uploading...</div>
                        </form>

                        @endif

                        @if (auth()->user()->role == 'admin')

                        <div class="card-head">
                            <h5 class="card-title">Hotel Income Records</h5>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
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
                                    @foreach($incomes as $income)
                                    <tr>
                                        <td>{{ $income->id }}</td>
                                        <td>{{ $income->date }}</td>
                                        <td>{{ $income->amount }}</td>
                                        <td>
                                        <button class="btn btn-primary" style="cursor: pointer;" onclick="openImageModal({{$income->proof}})">
                                            View Proof
                                        </button>
                                            
                                        </td>
                                        <td>{{ $income->is_approved ? 'Approved' : 'Pending' }}</td>
                                        <td>
                                            @if(!$income->is_approved)
                                                <button onclick="approveIncome({{ $income->id }})" class="btn btn-success btn-sm">Approve</button>
                                                <a href="{{ route('hotelincome.edit', $income->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                            @else
                                                <button class="btn btn-dark btn-sm" disabled>Approved</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer">
                            <h5>Total Income: {{ $totalIncome }}</h5>
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
    function openImageModal(imageUrls) {
        let container = document.getElementById('proofImagesContainer');
        container.innerHTML = ''; // Clear any previous images

        try {
            // Parse the `imageUrls` if it is a string
            if (typeof imageUrls === 'string') {
                imageUrls = JSON.parse(imageUrls);
            }

            // Check if imageUrls is a valid array
            if (Array.isArray(imageUrls) && imageUrls.length > 0) {
                imageUrls.forEach(url => {
                    let imgElement = document.createElement('img');
                    imgElement.src = '/storage/' + url; // Ensure storage URL is correct
                    imgElement.alt = 'Proof Image';
                    imgElement.classList.add('img-fluid', 'mb-2'); // Styling for images
                    container.appendChild(imgElement);
                });

                // Show the modal
                $('#imageModal').modal('show');
            } else {
                alert('No proof images found.');
            }
        } catch (error) {
            console.error('Error parsing image URLs:', error);
            alert('Could not load proof images.');
        }
    }

    function approveIncome(incomeId) {
        fetch(`/admin/approve-income/${incomeId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Income approved successfully!');
                location.reload();
            } else {
                alert(data.message || 'Failed to approve income.');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    document.getElementById('incomeForm').addEventListener('submit', function() {
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('uploadStatus').style.display = 'block';
    });
</script>

@endsection
