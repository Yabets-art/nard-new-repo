@extends('admin.layout')

@section('title', 'Messages')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Messages</h1>
    <p>View and respond to messages from customers.</p>

    <!-- List of messages -->
    <div class="card">
        <div class="card-header">Message Center</div>
        <div class="card-body">
            <ul class="list-group">
                @foreach($messages as $message)
                    <li class="list-group-item">
                        <a href="{{ route('admin.message.show', $message->id) }}">
                            {{ $message->name }} ({{ $message->email }})
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
