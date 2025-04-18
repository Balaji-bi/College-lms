@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f2f2f7;
    }

    .edit-form {
        background-color: #fff;
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        padding: 2rem;
        max-width: 700px;
        margin: auto;
    }

    .edit-form h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1c1c1e;
        margin-bottom: 1rem;
    }

    .edit-form label {
        display: block;
        font-weight: 500;
        color: #3a3a3c;
        margin-top: 1rem;
        margin-bottom: 0.3rem;
    }

    .edit-form input,
    .edit-form select,
    .edit-form textarea {
        width: 100%;
        border: 1px solid #d1d1d6;
        padding: 0.5rem 0.75rem;
        border-radius: 0.75rem;
        background-color: #f9f9f9;
        color: #1c1c1e;
    }

    .edit-form button {
        margin-top: 1.5rem;
        background-color: #007aff;
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        font-weight: 600;
        transition: background-color 0.2s ease;
    }

    .edit-form button:hover {
        background-color: #005ecb;
    }
</style>

<div class="edit-form">
    <h2>Edit Profile</h2>
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label for="department">Department</label>
        <input type="text" id="department" name="department" value="{{ old('department', $profile->department ?? '') }}">

        <label for="year">Year</label>
        <select id="year" name="year">
            @for ($i = 1; $i <= 4; $i++)
                <option value="{{ $i }}" {{ old('year', $profile->year ?? '') == $i ? 'selected' : '' }}>
                    Year {{ $i }}
                </option>
            @endfor
        </select>

        <label for="profile_picture">Profile Picture</label>
        <input type="file" id="profile_picture" name="profile_picture">

        <label for="recent_views">Recent Views (comma-separated)</label>
        <textarea id="recent_views" name="recent_views">{{ old('recent_views', $profile->recent_views ?? '') }}</textarea>

        <button type="submit">Save Changes</button>
    </form>
</div>
@endsection
