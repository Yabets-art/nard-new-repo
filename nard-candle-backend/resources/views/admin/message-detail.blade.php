@extends('admin.layout')

@section('title', 'Message Detail')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Message from {{ $message->name }}</h1>
    <p><strong>Email:</strong> {{ $message->email }}</p>
    <p><strong>Message:</strong> {{ $message->message }}</p>
    <a href="{{ route('admin.message.index') }}" class="btn btn-primary">Back to Messages</a>
@endsection
