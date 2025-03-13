<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="icon" href="{{ asset('assets/Travel.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
    <style>
        /* Table Styles */
        .table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            background-color: #ffffff;
            margin-bottom: 20px;
        }

        .table th, .table td {
            padding: 12px;
            text-align: left;
            vertical-align: middle;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #0D6EFD;
            color: #ffffff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        /* Status Badge Styles */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        .status-badge.pending {
            background-color: #ffc107;
            color: #000;
        }

        .status-badge.approved {
            background-color: #28a745;
            color: #fff;
        }

        .status-badge.declined {
            background-color: #dc3545;
            color: #fff;
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            font-size: 0.875rem;
        }

        .pagination .page-item {
            margin: 0 4px;
            list-style: none;
        }

        .pagination .page-item .page-link {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            color: #0D6EFD;
            text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .pagination .page-item.active .page-link {
            background-color: #0D6EFD;
            color: #fff;
            border-color: #0D6EFD;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #f8f9fa;
            border-color: #ddd;
        }

        .pagination .page-item .page-link:hover {
            background-color: #f1f1f1;
            color: #0D6EFD;
        }

        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            border-radius: 5px;
        }

        .pagination .page-item:first-child {
            margin-right: 10px;
        }

        .pagination .page-item:last-child {
            margin-left: 10px;
        }

        /* Pagination Info */
        .pagination-info {
            text-align: center;
            margin-top: 10px;
            font-size: 0.875rem;
            color: #6c757d;
        }

        /* Search and Filter Styles */
        .search-filter-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-filter-container .input-group {
            flex-grow: 1;
        }

        .search-filter-container .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 8px 12px;
            font-size: 0.875rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .search-filter-container .form-control:focus {
            border-color: #0D6EFD;
            box-shadow: 0 0 5px rgba(13, 110, 253, 0.5);
        }

        .search-filter-container select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
            padding-right: 30px;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .search-filter-container {
                flex-direction: column;
            }

            .search-filter-container .input-group {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        @include('owner/partials/aside')
        <div class="main p-3">
            <div class="text-center">
                <h1>Reviews</h1>
            </div>
            <div class="row justify-content-center mt-5">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Search and Filter Form -->
                    <form action="{{ url()->current() }}" method="GET" class="search-filter-container mb-3 d-flex gap-2">
                        <div class="input-group flex-grow-1" style="max-width: 300px;">
                            <input type="search" name="search" id="searchInput" placeholder="Search..." class="form-control" value="{{ request('search') }}">
                        </div>
                        <div class="input-group flex-grow-1" style="max-width: 200px;">
                            <select name="rating" id="ratingFilter" class="form-control">
                                <option value="">All Ratings</option>
                                <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3</option>
                                <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4</option>
                                <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" style="max-width: 100px;">Apply</button>
                    </form>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Company Name</th>
                                    <th>Destination</th>
                                    <th>Review Title</th>
                                    <th>Reviewer</th>
                                    <th>Ratings</th>
                                    <th>Date Created</th>
                                    <th>Status</th> <!-- New Status Column -->
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reviews as $review)
                                    <tr>
                                        <td>{{ ($reviews->currentPage() - 1) * $reviews->perPage() + $loop->iteration }}</td>
                                        <td>{{ $review->destination->company_name }}</td>
                                        <td>{{ $review->destination->destination_name }}</td>
                                        <td>{{ $review->review_title }}</td>
                                        <td>{{ $review->user->firstname }} {{ $review->user->lastname }}</td>
                                        <td>{{ $review->rating }}</td>
                                        <td>{{ $review->formatted_created_at }}</td>
                                        <td>
                                            <span class="status-badge 
                                                {{ $review->status === 'approved' ? 'approved' : 
                                                   ($review->status === 'pending' ? 'pending' : 
                                                   ($review->status === 'declined' ? 'declined' : 'secondary')) }}">
                                                {{ ucfirst($review->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <i class="lni lni-more" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                            <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#proofModal{{ $review->_id }}">View</a>
                                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#reportModal{{ $review->_id }}">Report</a>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal for Proof & Comment (Unique per Review) -->
                                    <div class="modal fade" id="proofModal{{ $review->_id }}" tabindex="-1" aria-labelledby="proofLabel{{ $review->_id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header bg-light">
                                                    <h1 class="modal-title fs-4 fw-bold text-dark" id="proofLabel{{ $review->_id }}">Proof & Comment</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @if($review->proof)
                                                        <img class="img-fluid rounded mb-4 shadow-sm" 
                                                             src="https://travelmate-be.onrender.com/{{ $review->proof }}" 
                                                             alt="Proof"
                                                             onerror="this.src='{{ asset('assets/placeholder.jpg') }}'; this.onerror=null;">
                                                    @else
                                                        <p class="text-muted">No proof image available</p>
                                                    @endif
                                                    <p class="text-muted mt-3">{{ $review->comment ?? 'No comment available' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

<!-- Report Modal (Unique per Review) -->
<div class="modal fade" id="reportModal{{ $review->_id }}" tabindex="-1" aria-labelledby="reportLabel{{ $review->_id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h1 class="modal-title fs-4 fw-bold text-dark" id="reportLabel{{ $review->_id }}">Report Review</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reportForm-{{ $review->_id }}" action="/owner/reports/store" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" value="{{ $review->id }}" name="review_id">
                    <input type="hidden" value="{{ $review->destination_id }}" name="destination_id">
                    <input type="hidden" name="reason" id="reasonHidden-{{ $review->_id }}">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Select a reason: <span class="text-danger">*</span></label>
                        
                        <div class="report-options">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="radio_temp" id="radio_false_{{ $review->_id }}" value="False information">
                                <label class="form-check-label" for="radio_false_{{ $review->_id }}">False information</label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="radio_temp" id="radio_offensive_{{ $review->_id }}" value="Offensive language">
                                <label class="form-check-label" for="radio_offensive_{{ $review->_id }}">Offensive language</label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="radio_temp" id="radio_spam_{{ $review->_id }}" value="Spam">
                                <label class="form-check-label" for="radio_spam_{{ $review->_id }}">Spam</label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="radio_temp" id="radio_conflict_{{ $review->_id }}" value="Conflict of interest">
                                <label class="form-check-label" for="radio_conflict_{{ $review->_id }}">Conflict of interest</label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="radio_temp" id="radio_privacy_{{ $review->_id }}" value="Privacy violation">
                                <label class="form-check-label" for="radio_privacy_{{ $review->_id }}">Privacy violation</label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="radio_temp" id="radio_irrelevant_{{ $review->_id }}" value="Irrelevant content">
                                <label class="form-check-label" for="radio_irrelevant_{{ $review->_id }}">Irrelevant content</label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="radio_temp" id="radio_threats_{{ $review->_id }}" value="Threats">
                                <label class="form-check-label" for="radio_threats_{{ $review->_id }}">Threats</label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="radio_temp" id="radio_others_{{ $review->_id }}" value="Others">
                                <label class="form-check-label" for="radio_others_{{ $review->_id }}">Others</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="explanation-{{ $review->_id }}" class="form-label fw-bold">Explanation: <span class="text-danger">*</span></label>
                        <textarea class="form-control"
                                  name="explanation"
                                  id="explanation-{{ $review->_id }}"
                                  placeholder="Please provide a detailed explanation for your report..."
                                  rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary w-100 fw-bold" type="submit">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No data yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <!-- Previous Button -->
                                <li class="page-item {{ $reviews->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $reviews->appends(request()->query())->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo; Previous</span>
                                    </a>
                                </li>

                                <!-- Page Numbers -->
                                @for ($i = 1; $i <= $reviews->lastPage(); $i++)
                                    <li class="page-item {{ $reviews->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $reviews->appends(request()->query())->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                <!-- Next Button -->
                                <li class="page-item {{ $reviews->hasMorePages() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $reviews->appends(request()->query())->nextPageUrl() }}" aria-label="Next">
                                        <span aria-hidden="true">Next &raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <!-- Pagination Info -->
                    <div class="pagination-info">
                        Showing {{ $reviews->firstItem() }} to {{ $reviews->lastItem() }} of {{ $reviews->total() }} results
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript logic -->
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle all report modals
    const reportModals = document.querySelectorAll('[id^="reportModal"]');
    
    reportModals.forEach(modal => {
        // Get the review ID from the modal ID (format: reportModal{{ $review->_id }})
        const modalId = modal.id;
        console.log('Found modal:', modalId);
        
        // Get form elements
        const form = modal.querySelector('form');
        if (!form) {
            console.error('Form not found in modal:', modalId);
            return;
        }
        
        console.log('Found form:', form.id);
        
        // Find radio buttons specifically within this modal
        const radioButtons = modal.querySelectorAll('input[type="radio"][name="radio_temp"]');
        console.log('Radio buttons found:', radioButtons.length);
        
        // Get the text area and hidden reason field
        const explanationField = modal.querySelector('textarea[name="explanation"]');
        const reasonHidden = modal.querySelector('input[name="reason"]');
        
        if (!explanationField || !reasonHidden) {
            console.error('Required fields not found:', {
                explanationField: !!explanationField,
                reasonHidden: !!reasonHidden
            });
            return;
        }
        
        // Add change listeners to each radio to verify they work
        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                console.log('Radio selected:', this.value);
            });
        });
        
        // Handle form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Form submission intercepted');
            
            // Check radio button selection directly from the DOM
            const selectedRadio = modal.querySelector('input[type="radio"][name="radio_temp"]:checked');
            console.log('Selected radio found:', !!selectedRadio);
            
            const explanation = explanationField.value.trim();
            console.log('Explanation text:', explanation);
            
            // Validate radio selection
            if (!selectedRadio) {
                console.log('No radio selected - showing error');
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Please select a reason for your report.',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            // Validate explanation
            if (explanation.length < 5) {
                console.log('Explanation too short - showing error');
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Please provide a more detailed explanation (at least 5 characters).',
                    confirmButtonText: 'OK'
                });
                explanationField.focus();
                return;
            }
            
            // Set the hidden reason field with the combined value
            const reportReason = selectedRadio.value + ': ' + explanation;
            reasonHidden.value = reportReason;
            console.log('Final reason:', reportReason);
            
            // Submit the form via AJAX
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || 'Report submitted successfully.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Close modal and reset form
                        form.reset();
                        const bsModal = bootstrap.Modal.getInstance(modal);
                        if (bsModal) bsModal.hide();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Failed to submit report.',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred. Please try again.',
                    confirmButtonText: 'OK'
                });
            });
        });
    });
});
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</body>
</html>