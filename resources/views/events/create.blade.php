<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Event</title>
    @vite('resources/css/style.css')
</head>
<body>

@include('includes.loginTopNav')

<section class="section-content">
    <h2 style="text-align:center;">Create New Event</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data" class="event-form">
        @csrf

        <!-- Event Posters -->
        <label>
            Event Posters * (max 4)
            <input type="file" name="posters[]" multiple accept="image/*">
            <small>Hold Ctrl (Windows) / Cmd (Mac) to select multiple images (up to 4)</small>
        </label>

        <!-- Event Name -->
        <label>
            Event Name *
            <input type="text" name="name" value="{{ old('name') }}" required>
        </label>

        <!-- Description -->
        <label>
            Description *
            <textarea name="description" rows="4" required>{{ old('description') }}</textarea>
        </label>

        <!-- Organizer -->
        <label>
            Organizer *
            <input type="text" name="organizer" value="{{ old('organizer') }}" required>
        </label>

        <!-- Contact Person -->
        <label>
            Contact Person
            <input type="text" name="contact_person" value="{{ old('contact_person') }}">
        </label>

        <!-- Contact Number -->
        <label>
            Contact No
            <input type="text" name="contact_no" value="{{ old('contact_no') }}">
        </label>

        <!-- Type -->
        <label>
            Event Type *
            <select name="type" required>
                <option value="">-- Select Type --</option>
                @foreach (['Workshop', 'Seminar', 'Competition', 'Festival', 'Sport', 'Course'] as $type)
                    <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
        </label>

        <!-- Venue -->
        <label>
            Venue *
            <input type="text" name="venue" value="{{ old('venue') }}" required>
        </label>

        <!-- Date -->
        <label>
            Date *
            <input type="date" name="date" value="{{ old('date') }}" required>
        </label>

        <!-- Time -->
        <label>
            Time *
            <input type="time" name="time" value="{{ old('time') }}" required>
        </label>

        <!-- Mode -->
        <label>
            Mode *
            <select name="mode" required>
                <option value="">-- Select Mode --</option>
                @foreach (['Physical', 'Online', 'Hybrid'] as $mode)
                    <option value="{{ $mode }}" {{ old('mode') == $mode ? 'selected' : '' }}>{{ $mode }}</option>
                @endforeach
            </select>
        </label>

        <!-- Registration Close -->
        <label>
            Registration Close Date *
            <input type="date" name="registration_close" value="{{ old('registration_close') }}" required>
        </label>

        <!-- Max Participants -->
        <label>
            Maximum Participants
            <input type="number" name="max_participants" value="{{ old('max_participants') }}" min="1">
        </label>

        <!-- Fee -->
        <label>
            Fee *
            <input type="text" name="fee" value="{{ old('fee') }}" placeholder="Free or amount in RM" required>
        </label>

        <!-- Remarks/Notes -->
        <label>
            Remarks / Notes
            <textarea name="remarks" rows="2">{{ old('remarks') }}</textarea>
        </label>

        

        <!-- Buttons -->
        <button type="submit" class="btn btn-primary">Create Event</button>
        <a href="{{ route('events.index') }}" class="btn btn-secondary">Back</a>
    </form>
</section>

</body>
</html>
