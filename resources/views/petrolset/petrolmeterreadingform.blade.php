@extends('layouts.dashboard')
@section('title', 'Petrol Meter Reading Form')
@section('content')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                {{-- Table Section --}}
                <div class="card mt-4">
                    <div class="card-inner">
                        <div class="card-head">
                            <h5 class="card-title">Meter Reading Records</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Meter Reading (Liters)</th>
                                        <th>Amount Received</th>
                                        <th>Proof Image</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($readings)
                                        @foreach ($readings as $reading)
                                        <tr>
                                            <td>{{ $reading->id }}</td>
                                            <td>{{ $reading->meter_reading }}</td>
                                            <td>{{ $reading->amount_received }}</td>
                                            <td>
                                            <button class="btn btn-primary" style="cursor: pointer;" onclick="openImageModal({{$reading->proof_image}})">
                                                            View Proof
                                                        </button>
                                            </td>
                                            <td>{{ $reading->created_at }}</td>
                                            <td>{{ $reading->is_approved ? 'Approved' : 'Pending' }}</td>
                                            <td>
                                                @if(!$reading->is_approved)
                                                    <button onclick="approveMeterReading({{ $reading->id }})" class="btn btn-success btn-sm">Approve</button>
                                                    <a href="{{ route('petrolmeter.edit', ['id' => $reading->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                                                @else
                                                    <button class="btn btn-dark btn-sm" disabled>Approved</button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">No records found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
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
    function approveMeterReading(meterId) {
        fetch(`/admin/approve-meter/${meterId}`, {
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
                alert('Meter reading approved successfully!');
                location.reload(); // Reload the page to reflect changes
            } else {
                alert(`Error: ${data.message || 'Failed to approve meter reading'}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred. Please try again.');
        });
    }

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
