<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body{
            background:#f5f6fa;
        }

        .card{
            border:none;
            border-radius:15px;
        }

        .stat-card{
            color:#fff;
            border-radius:15px;
        }

        .bg-blue{
            background:#0d6efd;
        }

        .bg-green{
            background:#198754;
        }

        .bg-orange{
            background:#fd7e14;
        }

        .table th{
            background:#212529;
            color:#fff;
        }

        .search-box{
            background:white;
            padding:20px;
            border-radius:15px;
        }

        .avatar{
            width:40px;
            height:40px;
            border-radius:50%;
            background:#0d6efd;
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:bold;
        }
    </style>

</head>

<body>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">

                <i class="bi bi-people-fill"></i>

                User Management

            </h2>

            <p class="text-muted">

                Search and manage registered users

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

                    <h6>Total Users</h6>

                    <h2>{{ $totalUsers }}</h2>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card stat-card bg-green shadow">

                <div class="card-body">

                    <h6>GitHub Users</h6>

                    <h2>{{ $githubUsers }}</h2>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card stat-card bg-orange shadow">

                <div class="card-body">

                    <h6>Email Users</h6>

                    <h2>{{ $emailUsers }}</h2>

                </div>

            </div>

        </div>

    </div>

    <!-- Search -->

    <div class="search-box shadow mb-4">

        <form method="GET" action="{{ route('users.index') }}">

            <div class="row">

                <div class="col-md-10">

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search by name or email..."
                        value="{{ request('search') }}">

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

    <!-- Users Table -->

    <div class="card shadow">

        <div class="card-header bg-dark text-white">

            <h5 class="mb-0">

                Registered Users

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

                            <th>Login Type</th>

                            <th>Joined</th>

                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($users as $user)

                        <tr>

                            <td>

                                {{ $user->id }}

                            </td>

                            <td>

                                <div class="d-flex align-items-center">

                                    <div class="avatar me-3">

                                        {{ strtoupper(substr($user->name,0,1)) }}

                                    </div>

                                    <div>

                                        <strong>

                                            {{ $user->name }}

                                        </strong>

                                        <br>

                                        <small class="text-muted">

                                            User ID :
                                            {{ $user->id }}

                                        </small>

                                    </div>

                                </div>

                            </td>

                            <td>

                                {{ $user->email }}

                            </td>

                            <td>

                                @if($user->github_id)

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

                                {{ $user->created_at->format('d M Y') }}

                            </td>

                            <td>

                                <span class="badge bg-success">

                                    Active

                                </span>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="6" class="text-center py-5">

                                <h5>No Users Found</h5>

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

                {{ $users->firstItem() ?? 0 }}

                to

                {{ $users->lastItem() ?? 0 }}

                of

                {{ $users->total() }}

                users

            </small>

        </div>

        <div>

         {{ $users->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}

        </div>

    </div>

    <!-- User Information Card -->

    <div class="card mt-5 shadow">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">

                <i class="bi bi-info-circle"></i>

                User Management Features

            </h5>

        </div>

        <div class="card-body">

            <div class="row text-center">

                <div class="col-md-3">

                    <i class="bi bi-search display-5 text-primary"></i>

                    <h6 class="mt-3">

                        Search

                    </h6>

                    <p class="text-muted">

                        Search users by name or email.

                    </p>

                </div>

                <div class="col-md-3">

                    <i class="bi bi-github display-5 text-dark"></i>

                    <h6 class="mt-3">

                        GitHub OAuth

                    </h6>

                    <p class="text-muted">

                        View GitHub connected users.

                    </p>

                </div>

                <div class="col-md-3">

                    <i class="bi bi-person-check display-5 text-success"></i>

                    <h6 class="mt-3">

                        Active Users

                    </h6>

                    <p class="text-muted">

                        Monitor registered users.

                    </p>

                </div>

                <div class="col-md-3">

                    <i class="bi bi-file-earmark-text display-5 text-warning"></i>

                    <h6 class="mt-3">

                        Pagination

                    </h6>

                    <p class="text-muted">

                        Easy navigation through records.

                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Footer -->

    <footer class="text-center mt-5 mb-3">

        <hr>

        <p class="text-muted">

            Laravel 12 GitHub Authentication Project

            <br>

            User Management Module

            <br>

            © {{ date('Y') }}

        </p>

    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>