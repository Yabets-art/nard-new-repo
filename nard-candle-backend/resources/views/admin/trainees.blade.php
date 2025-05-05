@extends('admin.layout')

@section('content')
    <div class="container mt-5">
        <h2>Trainees List</h2>

        @if ($trainees->isEmpty())
            <p class="alert alert-warning">No trainees found.</p>
        @else
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trainees as $index => $trainee)
                        <tr class="clickable-row" data-toggle="modal" data-target="#traineeModal{{ $trainee->id }}">
                            <td>{{ ($traineeszz->currentPage() - 1) * $trainees->perPage() + $index + 1 }}</td>
                            <td>{{ $trainee->first_name }} {{ $trainee->last_name }}</td>
                            <td>{{ $trainee->status }}</td>
                            <td>
                                <a href="{{ route('admin.trainees.show', $trainee->id) }}" class="btn btn-info btn-sm">View Details</a>
                            </td>
                        </tr>

                        <!-- Modal for extra information -->
                        <div class="modal fade" id="traineeModal{{ $trainee->id }}" tabindex="-1" role="dialog" aria-labelledby="traineeModalLabel{{ $trainee->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="traineeModalLabel{{ $trainee->id }}">Trainee Details</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <ul>
                                            <li><strong>Name:</strong> {{ $trainee->first_name }} {{ $trainee->last_name }}</li>
                                            <li><strong>Email:</strong> {{ $trainee->email }}</li>
                                            <li><strong>Phone:</strong> {{ $trainee->phone_number }}</li>
                                            <li><strong>Status:</strong> {{ $trainee->status }}</li>
                                            <li><strong>Paid:</strong> {{ $trainee->is_paid ? 'Yes' : 'No' }}</li>
                                            <li><strong>Training Day:</strong> {{ $trainee->training_day }}</li>
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-center">
                {{ $trainees->links() }}
            </div>
        @endif
    </div>

    <!-- Add Bootstrap and jQuery -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .clickable-row {
            cursor: pointer;
        }
    </style>
@endsection
