<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home | ECEs</title>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime&display=swap" rel="stylesheet">
    <style>
/* Base Styles */
    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f2f2f7;
        color: #333;
    }

    .profile-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Profile Header */
    .profile-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 30px;
    }

    .profile-image {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        margin-bottom: 15px;
        border: 3px solid #e0e0e0;
        background-color: #f0f0f0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .profile-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #e0e0e0;
        font-size: 60px;
        color: #8e8e93;
    }

    .profile-name {
        font-size: 28px;
        font-weight: 600;
        margin: 0;
    }

    /* Profile Details */
    .profile-details {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 30px;
        overflow: hidden;
    }

    .detail-item {
        padding: 15px 20px;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-size: 12px;
        font-weight: 500;
        color: #8e8e93;
        text-transform: uppercase;
    }

    .detail-value {
        font-size: 16px;
        font-weight: 500;
    }

    /* Recent Views */
    .recent-views h2 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #8e8e93;
    }

    .views-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .view-card {
        background-color: white;
        border-radius: 10px;
        height: 180px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
    }
    .header h1 {
        font-size: 2.2rem;
        color: #444;
    }
    .nav-buttons {
        display: flex;
        align-items: center;
    }
    .nav-buttons button {
        margin-left: 10px;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        background-color: #e0e0e0;
        color: #222;
        cursor: pointer;
        font-weight: bold;
    }
    .nav-buttons button:hover {
        background-color: #ccc;
    }
    .modal-content {
        background-color: #f8f8f8;
        margin: 10% auto;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        width: 90%;
        max-width: 500px;
        position: relative;
    }

    .close {
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 24px;
        font-weight: bold;
        color: #8e8e93;
        cursor: pointer;
    }

    .modal h2 {
        margin-top: 0;
        margin-bottom: 20px;
        color: #333;
        text-align: center;
        font-size: 24px;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #636366;
    }

    .form-group input[type="text"],
    .form-group input[type="file"],
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid #d1d1d6;
        border-radius: 8px;
        background-color: white;
        font-size: 16px;
        box-sizing: border-box;
    }

    .radio-group {
        display: flex;
        gap: 20px;
    }

    .radio-group label {
        display: flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
    }

    .btn-submit {
        width: 100%;
        padding: 12px;
        background-color: #8e8e93;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-submit:hover {
        background-color: #7d7d82;
    }

    #preview-container {
        margin-top: 10px;
        text-align: center;
    }

    #preview-container img {
        max-width: 100%;
        max-height: 200px;
        border-radius: 5px;
    }

    .hidden {
        display: none;
    }
    </style>
</head>
<body>

<div class="header">
            
    <div class="nav-buttons">
        <a href="{{ url('/') }}"><button>HOME</button></a>
        <a href="{{ url('/chat') }}"><button>AI CHAT</button></a>
        <div class="profile-icon">
            <a href="{{ route('profile.show') }}" class="profile-link">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/profile_photos/'.auth()->user()->profile_photo) }}" alt="Profile">
                @else
                    <div class="profile-icon">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</div>
                @endif
            </a>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn">
                Logout
            </button>
        </form>

    </div>
</div>
<div class="profile-container">
    <div class="profile-header">
        <div class="profile-image">
            @if($user->profile_photo)
                <img src="{{ asset('storage/profile_photos/'.$user->profile_photo) }}" alt="Profile Photo">
            @else
                <div class="profile-placeholder">
                    <span>{{ substr($user->name ?? 'U', 0, 1) }}</span>
                </div>
            @endif
        </div>
        <h1 class="profile-name">{{ $user->name ?? 'Name' }}</h1>
    </div>
    
    <div class="profile-details">
        <div class="detail-item">
            <div class="detail-label">DEPARTMENT DETAILS</div>
            <div class="detail-value">ECEs</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">YEAR</div>
            <div class="detail-value">{{ $user->year ?? '-' }}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">COLLEGE</div>
            <div class="detail-value">{{ $user->college ?? '-' }}</div>
        </div>
    </div>
    
    <div class="recent-views">
        <h2>RECENT VIEWS</h2>
        <div class="views-grid">
            <div class="view-card"></div>
            <div class="view-card"></div>
            <div class="view-card"></div>
        </div>
    </div>
</div>

<!-- Profile Completion Modal -->
<div id="profileModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>ECE Students</h2>
        
        <form id="profileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="profile_photo">Profile Photo (optional)</label>
                <input type="file" name="profile_photo" id="profile_photo">
                <div id="preview-container" class="hidden">
                    <img id="photo-preview" src="#" alt="Preview">
                </div>
            </div>
            
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" value="{{ $user->name ?? '' }}" required>
            </div>
            
            <div class="form-group">
                <label for="year">Year of Studying</label>
                <select name="year" id="year" required>
                    <option value="" disabled selected>Select Year</option>
                    <option value="1st Year" {{ $user->year == '1st Year' ? 'selected' : '' }}>1st Year</option>
                    <option value="2nd Year" {{ $user->year == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                    <option value="3rd Year" {{ $user->year == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                    <option value="4th Year" {{ $user->year == '4th Year' ? 'selected' : '' }}>4th Year</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone No. (optional)</label>
                <input type="text" name="phone" id="phone" value="{{ $user->phone ?? '' }}">
            </div>
            
            <div class="form-group">
                <label>Role</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="role" value="student" {{ $user->role == 'student' ? 'checked' : '' }} required>
                        Student
                    </label>
                    <label>
                        <input type="radio" name="role" value="staff" {{ $user->role == 'staff' ? 'checked' : '' }} required>
                        Staff
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn-submit">Save</button>
            </div>
        </form>
    </div>
</div>
</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get the modal
    var modal = document.getElementById('profileModal');
    var span = document.getElementsByClassName('close')[0];
    
    // Check if profile is complete
    fetch('{{ route("profile.check-completion") }}')
        .then(response => response.json())
        .then(data => {
            if (!data.isComplete) {
                modal.style.display = "block";
            }
        });
    
    // Close the modal when clicking on X
    span.onclick = function() {
        modal.style.display = "none";
    }
    
    // Close the modal when clicking outside
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    
    // Preview image
    const photoInput = document.getElementById('profile_photo');
    const previewContainer = document.getElementById('preview-container');
    const photoPreview = document.getElementById('photo-preview');
    
    photoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            
            reader.addEventListener('load', function() {
                photoPreview.setAttribute('src', this.result);
                previewContainer.classList.remove('hidden');
            });
            
            reader.readAsDataURL(file);
        }
    });
});
</script>
</html>



