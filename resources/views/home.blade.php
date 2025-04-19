<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home | ECEs</title>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Courier Prime', monospace;
            background-color: #f2f2f2;
            margin: 0;
            display: flex;
            color: #333;
        }
        .sidebar {
            width: 240px;
            background-color: #dcdcdc;
            padding: 30px 20px;
            height: 100vh;
        }
        .sidebar a {
            display: block;
            margin: 15px 0;
            color: #444;
            text-decoration: none;
        }
        .sidebar a:hover {
            color: #111;
        }
        .main {
            flex: 1;
            padding: 30px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
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
        
        .card {
            background: #eaeaea;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .dots {
            margin-top: 10px;
        }
        .dots span {
            display: inline-block;
            width: 8px;
            height: 8px;
            margin: 0 4px;
            background-color: #777;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="#">Assignment Gen</a>
        <a href="#">Paper Create</a>
        <a href="#">Resume Gen</a>
        <a href="#">Image Gen</a>
        <a href="#">Rewriting Content</a>
    </div>

    <div class="main">
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

        <div class="card">
            <div>AU news</div>
            <div class="dots"><span></span><span></span><span></span></div>
        </div>
        <div class="card">
            <div>Industry and technology news</div>
            <div class="dots"><span></span><span></span><span></span></div>
        </div>

        <div class="grid">
            <div class="card">Syllabus</div>
            <div class="card">FORUM</div>
        </div>
    </div>
</body>
</html>
