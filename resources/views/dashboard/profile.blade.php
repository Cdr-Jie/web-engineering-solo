<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
    @vite('resources/css/style.css')
</head>
<body>

@include('includes.loginTopNav')

<section class="section-content">

    <h2 style="text-align:center;">Update Profile</h2>

<form method="POST" action="{{ route('profile.update') }}" class="profile-form" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Profile Picture -->
    <div style="margin-bottom: 20px; text-align: center;">
        <label for="profile_image">Profile Picture</label><br>
        @if(Auth::user()->profile_image)
            <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" 
                 alt="Profile Picture" 
                 style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin: 10px 0;">
            <p style="font-size: 0.9em; color: #666;">Current profile picture</p>
        @else
            <div style="width: 150px; height: 150px; border-radius: 50%; background: #e0e0e0; margin: 10px auto; display: flex; align-items: center; justify-content: center;">
                <p style="color: #999;">No picture yet</p>
            </div>
        @endif
        <br>
        <input type="file" id="profile_image" name="profile_image" accept="image/jpeg,image/png,image/jpg,image/gif" style="margin-top: 10px;">
        <p style="font-size: 0.85em; color: #666;">JPG, PNG, or GIF, max 2MB</p>
    </div>

    <!-- Name -->
    <label>
        Name
        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required>
    </label>

    <!-- Email -->
    <label>
        Email
        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
    </label>

    <!-- Password -->
    <label>
        New Password
        <input type="password" name="password" placeholder="Leave blank to keep current password">
    </label>

    <!-- Phone -->
    <label>
        Phone
        <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}">
    </label>

    <!-- Category -->
    <label>
        Category
        <select name="category" required>
            <option value="">-- Select Category --</option>
            <option value="staff" {{ old('category', Auth::user()->category) == 'staff' ? 'selected' : '' }}>Staff</option>
            <option value="student" {{ old('category', Auth::user()->category) == 'student' ? 'selected' : '' }}>Student</option>
            <option value="public" {{ old('category', Auth::user()->category) == 'public' ? 'selected' : '' }}>Public</option>
        </select>
    </label>

    <!-- Event Type -->
    <label>
        Preferred Event Type
        <select name="events" required>
            <option value="">-- Select Event Type --</option>
            @foreach (['Workshop', 'Seminar', 'Competition', 'Festival', 'Sport', 'Course'] as $event)
                <option value="{{ $event }}"
                    {{ old('events', Auth::user()->events) == $event ? 'selected' : '' }}>
                    {{ $event }}
                </option>
            @endforeach
        </select>
    </label>
    
    <button type="submit" class="btn btn-primary">Save Changes</button>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back</a>
</form>