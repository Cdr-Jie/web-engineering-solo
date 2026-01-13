@extends('layouts.app')

@section('content')
<div style="margin-top: 30px; margin-bottom: 30px;">
    @php
        $backUrl = url()->previous();
        $backText = 'Events';
        
        // Determine back button text and URL based on referrer
        if (str_contains($backUrl, '/dashboard')) {
            $backText = 'Dashboard';
        } elseif (str_contains($backUrl, '/my-registrations')) {
            $backText = 'My Registrations';
        } elseif (str_contains($backUrl, '/my-events')) {
            $backText = 'My Events';
        }
    @endphp
    
    <a href="{{ $backUrl }}" class="btn" style="margin-bottom: 20px;">← Back to {{ $backText }}</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div style="max-width: 900px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        
        {{-- Event Poster --}}
        @if(!empty($event->posters) && count($event->posters) > 0)
            <div style="margin-bottom: 20px;">
                <img src="{{ asset('storage/' . $event->posters[0]) }}" 
                     alt="{{ $event->name }} Poster" 
                     style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 10px;">
            </div>
        @endif

        {{-- Event Title --}}
        <h1 style="margin-bottom: 10px;">{{ $event->name }}</h1>
        <p style="color: #666; font-size: 0.95em; margin-bottom: 20px;">
            <strong>Type:</strong> {{ $event->type }} | <strong>Mode:</strong> {{ $event->mode }}
        </p>

        {{-- Key Details Grid --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; padding: 20px; background: #f9f9f9; border-radius: 8px;">
            <div>
                <p><strong>📅 Date:</strong> {{ $event->date->format('d M Y') }}</p>
                <p><strong>⏰ Time:</strong> {{ $event->time }}</p>
            </div>
            <div>
                <p><strong>📍 Venue:</strong> {{ $event->venue }}</p>
                <p><strong>💰 Fee:</strong> {{ $event->fee }}</p>
            </div>
            <div>
                <p><strong>🗓️ Registration Closes:</strong> {{ $event->registration_close->format('d M Y') }}</p>
                <p><strong>👥 Max Participants:</strong> {{ $event->max_participants ?? 'Unlimited' }}</p>
            </div>
            <div>
                <p><strong>Organizer:</strong> {{ $event->organizer }}</p>
                @if($event->contact_person)
                    <p><strong>Contact Person:</strong> {{ $event->contact_person }}</p>
                @endif
            </div>
        </div>

        {{-- Contact Info --}}
        @if($event->contact_no)
            <div style="margin-bottom: 20px; padding: 15px; background: #e8f4f8; border-left: 4px solid #3498db; border-radius: 5px;">
                <p><strong>📞 Contact Number:</strong> {{ $event->contact_no }}</p>
            </div>
        @endif

        {{-- Description --}}
        <div style="margin-bottom: 20px;">
            <h3>Description</h3>
            <p style="line-height: 1.6; color: #333;">{{ $event->description }}</p>
        </div>

        {{-- Remarks --}}
        @if($event->remarks)
            <div style="margin-bottom: 20px; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 5px;">
                <h4>📌 Remarks</h4>
                <p>{{ $event->remarks }}</p>
            </div>
        @endif

        {{-- Action Buttons --}}
        <div style="display: flex; gap: 10px; margin-top: 30px; flex-wrap: wrap;">
            {{-- Only show edit/delete if user owns the event --}}
            @if($event->user_id === Auth::id())
                <a href="{{ route('events.edit', $event) }}" class="btn" style="background: #3498db;">Edit Event</a>
                <form action="{{ route('events.destroy', $event) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn" style="background: #e74c3c;" 
                            onclick="return confirm('Are you sure you want to delete this event?')">Delete Event</button>
                </form>
            @else
                {{-- Registration Actions --}}
                @php
                    $registered = $event->registrations()->where('user_id', Auth::id())->exists();
                @endphp

                @if($registered)
                    <form action="{{ route('events.unregister', $event->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" style="background: #e74c3c;" 
                                onclick="return confirm('Are you sure you want to unregister from this event?')">Unregister</button>
                    </form>
                @else
                    <button class="btn" style="background: #27ae60; color: white; padding: 12px 24px; font-weight: bold;" 
                            onclick="openRegisterModal({{ $event->id }}, '{{ $event->name }}', '{{ $event->fee }}')">
                        Register for Event
                    </button>
                @endif
            @endforelse
        </div>

    </div>
</div>

<!-- Registration Modal -->
<div id="registerModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
    background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index: 1000;">
    <div style="background:#fff; padding:30px; border-radius:10px; max-width:500px; width:90%; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
        <h3 id="modalEventName"></h3>
        <form method="POST" action="{{ route('events.register') }}">
            @csrf
            <input type="hidden" name="event_id" id="modalEventId">

            <label style="display: block; margin-bottom: 15px;">
                <strong>Full Name</strong><br>
                <input type="text" name="name" value="{{ Auth::check() ? Auth::user()->name : '' }}" required style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px;">
            </label>

            <label style="display: block; margin-bottom: 15px;">
                <strong>Email</strong><br>
                <input type="email" name="email" value="{{ Auth::check() ? Auth::user()->email : '' }}" required style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px;">
            </label>

            <label style="display: block; margin-bottom: 20px;">
                <strong>Payment Amount (RM)</strong><br>
                <input type="number" name="payment" id="modalPayment" min="0" step="0.01" required style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px;">
            </label>

            <button type="submit" class="btn" style="background: #27ae60; color: white; width: 100%; padding: 10px; margin-bottom: 10px;">Confirm Registration</button>
            <button type="button" class="btn" style="background: #95a5a6; color: white; width: 100%; padding: 10px;" onclick="closeRegisterModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
function openRegisterModal(eventId, eventName, fee) {
    document.getElementById('registerModal').style.display = 'flex';
    document.getElementById('modalEventName').innerText = 'Register for: ' + eventName;
    document.getElementById('modalEventId').value = eventId;
    document.getElementById('modalPayment').value = fee === 'Free' ? 0 : fee;
}

function closeRegisterModal() {
    document.getElementById('registerModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('registerModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRegisterModal();
    }
});
</script>
@endsection
