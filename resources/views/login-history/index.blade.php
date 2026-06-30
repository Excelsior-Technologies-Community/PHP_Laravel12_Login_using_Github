<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login History</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
        }

        .card {
            border: none;
            border-radius: 15px;
        }

        .stat-card {
            color: #fff;
            border-radius: 15px;
        }

        .bg-blue {
            background: #0d6efd;
        }

        .bg-dark-custom {
            background: #212529;
        }

        .bg-success-custom {
            background: #198754;
        }

        .search-box {
            background: white;
            border-radius: 15px;
            padding: 20px;
        }

        .table th {
            background: #212529;
            color: #fff;
        }

        code {
            font-size: 13px;
        }
    </style>

</head>

<body>

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold">

                    <i class="bi bi-clock-history"></i>

                    Login History

                </h2>

                <p class="text-muted">

                    Monitor all user login activities.

                </p>

            </div>

            <a href="{{ route('dashboard') }}" class="btn btn-primary">

                <i class="bi bi-arrow-left"></i>

                Dashboard

            </a>

        </div>

        <!-- Statistics -->

        <div class="row mb-4">

            <div class="col-md-4">

                <div class="card stat-card bg-blue shadow">

                    <div class="card-body">

                        <h6>Total Logins</h6>

                        <h2>{{ $totalLogins }}</h2>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card stat-card bg-dark-custom shadow">

                    <div class="card-body">

                        <h6>GitHub Logins</h6>

                        <h2>{{ $githubLogins }}</h2>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card stat-card bg-success-custom shadow">

                    <div class="card-body">

                        <h6>Email Logins</h6>

                        <h2>{{ $emailLogins }}</h2>

                    </div>

                </div>

            </div>

        </div>

        <!-- Search -->

        <div class="search-box shadow mb-4">

            <form method="GET">

                <div class="row">

                    <div class="col-md-10">

                        <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                            placeholder="Search by user name, email or login method">

                    </div>

                    <div class="col-md-2 d-grid">

                        <button class="btn btn-primary">

                            <i class="bi bi-search"></i>

                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

        <!-- Table -->

        <div class="card shadow">

            <div class="card-header bg-dark text-white">

                <h5 class="mb-0">

                    Login Records

                </h5>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>

                            <tr>

                                <th>ID</th>

                                <th>User</th>

                                <th>Email</th>

                                <th>Method</th>

                                <th>IP Address</th>

                                <th>Browser</th>

                                <th>Login Time</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($histories as $history)

                                <tr>

                                    <td>{{ $history->id }}</td>

                                    <td>{{ $history->user->name ?? '-' }}</td>

                                    <td>{{ $history->user->email ?? '-' }}</td>

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

                                    <td>

                                        <code>{{ $history->ip_address }}</code>

                                    </td>

                                    <td>

                                        {{ \Illuminate\Support\Str::limit($history->browser, 40) }}

                                    </td>

                                    <td>

                                        {{ \Carbon\Carbon::parse($history->login_at)->format('d M Y h:i A') }}

                                    </td>

                                </tr>

                            @empty
                                <tr>

                                    <td colspan="7" class="text-center py-5">

                                        <i class="bi bi-clock-history display-4 text-secondary"></i>

                                        <h5 class="mt-3">

                                            No Login History Found

                                        </h5>

                                        <p class="text-muted">

                                            Login records will appear here after users sign in.

                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <!-- Pagination -->

        <div class="d-flex justify-content-between align-items-center mt-4">

            <div>

                <small class="text-muted">

                    Showing

                    {{ $histories->firstItem() ?? 0 }}

                    to

                    {{ $histories->lastItem() ?? 0 }}

                    of

                    {{ $histories->total() }}

                    login records

                </small>

            </div>

            <!-- Pagination -->

            <div class="d-flex justify-content-center align-items-center mt-4">

                <nav>

                    <ul class="pagination mb-0">

                        @for ($i = 1; $i <= $histories->lastPage(); $i++)

                            <li class="page-item {{ $histories->currentPage() == $i ? 'active' : '' }}">

                                <a class="page-link" href="{{ $histories->withQueryString()->url($i) }}">

                                    {{ $i }}

                                </a>

                            </li>

                        @endfor

                    </ul>

                </nav>

            </div>

        </div>

        <!-- Information Cards -->

        <div class="row mt-5">

            <div class="col-md-4 mb-3">

                <div class="card shadow-sm h-100">

                    <div class="card-body text-center">

                        <i class="bi bi-shield-check display-5 text-success"></i>

                        <h5 class="mt-3">

                            Secure Tracking

                        </h5>

                        <p class="text-muted">

                            Every successful login is recorded with the login method,
                            IP address, browser information, and timestamp.

                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-4 mb-3">

                <div class="card shadow-sm h-100">

                    <div class="card-body text-center">

                        <i class="bi bi-github display-5 text-dark"></i>

                        <h5 class="mt-3">

                            GitHub OAuth

                        </h5>

                        <p class="text-muted">

                            Easily distinguish between GitHub OAuth logins
                            and traditional email/password logins.

                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-4 mb-3">

                <div class="card shadow-sm h-100">

                    <div class="card-body text-center">

                        <i class="bi bi-bar-chart-line display-5 text-primary"></i>

                        <h5 class="mt-3">

                            Login Analytics

                        </h5>

                        <p class="text-muted">

                            Review user login activity for monitoring
                            and administrative reporting.

                        </p>

                    </div>

                </div>

            </div>

        </div>

        <!-- Footer -->

        <footer class="text-center mt-5">

            <hr>

            <h6 class="fw-bold">

                Laravel 12 GitHub Authentication System

            </h6>

            <p class="text-muted mb-1">

                Login History Module

            </p>

            <small class="text-secondary">

                © {{ date('Y') }} All Rights Reserved

            </small>

        </footer>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>