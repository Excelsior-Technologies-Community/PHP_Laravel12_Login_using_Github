<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Laravel GitHub Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f4f6f9;
            font-family: Arial, Helvetica, sans-serif;
        }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #212529;
            position: fixed;
            left: 0;
            top: 0;
            color: #fff;
        }

        .sidebar h4 {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
        }

        .sidebar a {
            color: #ddd;
            text-decoration: none;
            display: block;
            padding: 15px 20px;
            transition: .3s;
        }

        .sidebar a:hover {
            background: #0d6efd;
            color: white;
        }

        .content {
            margin-left: 260px;
            padding: 30px;
        }

        .card-box {
            border-radius: 15px;
            border: none;
            color: white;
        }

        .card-blue {
            background: #0d6efd;
        }

        .card-green {
            background: #198754;
        }

        .card-orange {
            background: #fd7e14;
        }

        .card-purple {
            background: #6f42c1;
        }

        .profile {
            text-align: center;
            padding: 20px;
        }

        .profile img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
        }

        .table {
            background: white;
        }

        .welcome-card {
            border: none;
            border-radius: 15px;
        }

        .activity {
            max-height: 300px;
            overflow: auto;
        }
    </style>

</head>

<body>

    <div class="sidebar">

        <h4>
            <i class="bi bi-github"></i>
            Laravel OAuth
        </h4>

        <div class="profile">

            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D6EFD&color=fff">

            <h5 class="mt-3">
                {{ Auth::user()->name }}
            </h5>

            <small>
                {{ Auth::user()->email }}
            </small>

            <br>

            @if(Auth::user()->github_id)

                <span class="badge bg-success mt-2">
                    GitHub Connected
                </span>

            @else

                <span class="badge bg-primary mt-2">
                    Email Login
                </span>

            @endif

        </div>

        <a href="{{ route('dashboard') }}">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </a>

        <a href="{{ route('users.index') }}">
            <i class="bi bi-people"></i>
            Users
        </a>

        <a href="{{ route('login.history') }}">
            <i class="bi bi-clock-history"></i>
            Login History
        </a>

        <a href="#">
            <i class="bi bi-person"></i>
            Profile
        </a>

        <a href="#">
            <i class="bi bi-gear"></i>
            Settings
        </a>

        <hr class="text-secondary">

        <form action="{{ route('logout') }}" method="POST" class="px-3">
            @csrf

            <button class="btn btn-danger w-100">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </button>

        </form>

    </div>

    <div class="content">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold">
                    Dashboard
                </h2>

                <p class="text-muted">
                    Welcome back {{ Auth::user()->name }}
                </p>

            </div>

            <div>

                <span class="badge bg-dark p-2">

                    <i class="bi bi-calendar"></i>

                    {{ now()->format('d M Y') }}

                </span>

            </div>

        </div><!-- Welcome Card -->
        <div class="card welcome-card shadow-sm mb-4">
            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-md-8">

                        <h3 class="fw-bold">
                            👋 Welcome, {{ Auth::user()->name }}
                        </h3>

                        <p class="text-muted mb-0">
                            You are successfully logged into the Laravel GitHub Authentication System.
                        </p>

                    </div>

                    <div class="col-md-4 text-end">

                        @if(Auth::user()->github_id)

                            <span class="badge bg-success fs-6">
                                GitHub OAuth Login
                            </span>

                        @else

                            <span class="badge bg-primary fs-6">
                                Email Login
                            </span>

                        @endif

                    </div>

                </div>

            </div>
        </div>

        <!-- Dashboard Statistics -->
        <div class="row">

            <div class="col-md-3 mb-4">

                <div class="card card-box card-blue shadow">

                    <div class="card-body">

                        <h6>Total Users</h6>

                        <h1 class="fw-bold">
                            {{ $totalUsers }}
                        </h1>

                        <small>Registered Accounts</small>

                    </div>

                </div>

            </div>

            <div class="col-md-3 mb-4">

                <div class="card card-box card-green shadow">

                    <div class="card-body">

                        <h6>GitHub Users</h6>

                        <h1 class="fw-bold">
                            {{ $githubUsers }}
                        </h1>

                        <small>OAuth Logins</small>

                    </div>

                </div>

            </div>

            <div class="col-md-3 mb-4">

                <div class="card card-box card-orange shadow">

                    <div class="card-body">

                        <h6>Email Users</h6>

                        <h1 class="fw-bold">
                            {{ $normalUsers }}
                        </h1>

                        <small>Manual Registration</small>

                    </div>

                </div>

            </div>

            <div class="col-md-3 mb-4">

                <div class="card card-box card-purple shadow">

                    <div class="card-body">

                        <h6>Today's Users</h6>

                        <h1 class="fw-bold">
                            {{ $todayUsers }}
                        </h1>

                        <small>Joined Today</small>

                    </div>

                </div>

            </div>

        </div>

        <!-- Account Information -->
        <div class="row">

            <div class="col-lg-6 mb-4">

                <div class="card shadow border-0">

                    <div class="card-header bg-primary text-white">

                        <h5 class="mb-0">
                            Account Information
                        </h5>

                    </div>

                    <div class="card-body">

                        <table class="table table-borderless">

                            <tr>
                                <th width="40%">Name</th>
                                <td>{{ Auth::user()->name }}</td>
                            </tr>

                            <tr>
                                <th>Email</th>
                                <td>{{ Auth::user()->email }}</td>
                            </tr>

                            <tr>
                                <th>Member Since</th>
                                <td>{{ Auth::user()->created_at->format('d M Y') }}</td>
                            </tr>

                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge bg-success">
                                        Active
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <th>Login Method</th>
                                <td>

                                    @if(Auth::user()->github_id)

                                        <span class="badge bg-dark">
                                            GitHub OAuth
                                        </span>

                                    @else

                                        <span class="badge bg-primary">
                                            Email Login
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>

            </div>

            <!-- Quick Actions -->
            <div class="col-lg-6 mb-4">

                <div class="card shadow border-0">

                    <div class="card-header bg-success text-white">

                        <h5 class="mb-0">
                            Quick Actions
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="d-grid gap-3">

                            <a href="{{ route('users.index') }}" class="btn btn-outline-primary">

                                <i class="bi bi-people-fill"></i>

                                Manage Users

                            </a>

                            <a href="{{ route('login.history') }}" class="btn btn-outline-success">

                                <i class="bi bi-clock-history"></i>

                                Login History

                            </a>

                            <button class="btn btn-outline-secondary">

                                <i class="bi bi-person-circle"></i>

                                View Profile

                            </button>

                            <button class="btn btn-outline-dark">

                                <i class="bi bi-gear-fill"></i>

                                Settings

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div><!-- Recent Activity -->
        <div class="row">

            <div class="col-lg-12">

                <div class="card shadow border-0">

                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">
                            <i class="bi bi-clock-history"></i>
                            Recent Login Activity
                        </h5>

                        <a href="{{ route('login.history') }}" class="btn btn-light btn-sm">
                            View All
                        </a>

                    </div>

                    <div class="card-body">

                        @if(Auth::user()->loginHistories->count())

                            <div class="table-responsive">

                                <table class="table table-hover align-middle">

                                    <thead class="table-light">

                                        <tr>

                                            <th>#</th>

                                            <th>Login Method</th>

                                            <th>IP Address</th>

                                            <th>Browser</th>

                                            <th>Login Time</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        @foreach(Auth::user()->loginHistories->take(5) as $history)

                                            <tr>

                                                <td>{{ $loop->iteration }}</td>

                                                <td>

                                                    @if($history->login_method == 'GitHub')

                                                        <span class="badge bg-dark">

                                                            <i class="bi bi-github"></i>

                                                            GitHub

                                                        </span>

                                                    @else

                                                        <span class="badge bg-primary">

                                                            Email

                                                        </span>

                                                    @endif

                                                </td>

                                                <td>{{ $history->ip_address }}</td>

                                                <td>

                                                    {{ Str::limit($history->browser, 40) }}

                                                </td>

                                                <td>

                                                    {{ \Carbon\Carbon::parse($history->login_at)->format('d M Y h:i A') }}

                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        @else

                            <div class="text-center py-5">

                                <i class="bi bi-clock-history display-4 text-secondary"></i>

                                <h5 class="mt-3">

                                    No Login History Found

                                </h5>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

        <!-- Footer -->
        <div class="mt-5">

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center">

                    <h5 class="mb-2">

                        Laravel 12 GitHub Authentication Project

                    </h5>

                    <p class="text-muted mb-2">

                        Dashboard with GitHub OAuth, User Management, Login History and Analytics.

                    </p>

                    <small class="text-secondary">

                        © {{ date('Y') }} Laravel Dashboard. All Rights Reserved.

                    </small>

                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>