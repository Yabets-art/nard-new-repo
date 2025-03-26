@extends('admin.layout')

@section('content')
    <div class="container mt-5">
        <h2>Manage Training Days</h2>

        <form method="POST" action="{{ route('admin.training-days.update') }}">
            @csrf
            <ul>
                @foreach($days as $day)
                    <li>
                        <label>
                            <input type="checkbox" name="days[]" value="{{ $day->id }}" {{ $day->is_available ? 'checked' : '' }}>
                            {{ $day->day }}
                        </label>
                    </li>
                @endforeach
            </ul>
            <button type="submit" class="btn btn-primary">Update Days</button>
        </form>
        
        
    </div>
@endsection
