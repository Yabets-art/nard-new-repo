@extends('admin.layout')

@section('title', 'Messages')

@section('content')
    <h1 class="h3 mb-4 text-primary">📩 Messages</h1>
    <p class="text-muted">View and respond to messages from customers.</p>

    <!-- Messages Table -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Message Center</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Sender</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages->sortByDesc('created_at') as $index => $message)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $message->name }}</td>
                            <td>{{ $message->email }}</td>
                            <td>
                                {{ $message->created_at ? $message->created_at->format('M d, Y h:i A') : 'N/A' }}
                            </td>
                            <td>
                                <a href="{{ route('admin.message.show', $message->id) }}" class="btn btn-sm btn-info">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    @if($messages->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center text-muted">No messages yet.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
