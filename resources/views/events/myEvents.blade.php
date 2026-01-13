@extends('layouts.app')

@section('content')
<div class="my-events-header">
    <h2>📅 My Events</h2>
    <a href="{{ route('events.create') }}" class="btn">Create Event</a>
</div>

@if($events->isEmpty())
    <p>You haven’t created any events yet.</p>
@else
    <div class="dashboard-grid">
        @foreach($events as $event)

            <div class="dashboard-card">
                <h3>{{ $event->name }}</h3>

                {{-- Event poster preview --}}
                @if(!empty($event->posters) && count($event->posters) > 0)
                    <img src="{{ asset('storage/' . $event->posters[0]) }}" 
                        alt="{{ $event->name }} Poster" 
                        class="event-poster">
                @endif

                <p>{{ $event->date->format('d M Y') }} | {{ $event->time }}</p>
                <p>{{ $event->venue }}</p>


                <a href="{{ route('events.edit', $event) }}" class="btn">Edit</a>

                <form action="{{ route('events.destroy', $event) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn danger">Delete</button>
                </form>
            </div>
        @endforeach
    </div>

    <div class="create-event-button-bottom">
        <a href="{{ route('events.create') }}" class="btn">Create New Event</a>
    </div>
@endif
@endsection
