<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GitHub Orgs & Commits Activity Timeline | Laravel OAuth Studio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background: #f4f6f9; font-family: Arial, Helvetica, sans-serif; }
        .sidebar { width: 260px; min-height: 100vh; background: #212529; position: fixed; left: 0; top: 0; color: #fff; }
        .sidebar h4 { padding: 20px; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar a { color: #ddd; text-decoration: none; display: block; padding: 15px 20px; transition: .3s; }
        .sidebar a:hover, .sidebar a.active { background: #0d6efd; color: white; }
        .content { margin-left: 260px; padding: 30px; }
        .card { border-radius: 15px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4><i class="bi bi-github"></i> Laravel OAuth</h4>
        <div class="text-center p-3">
            <img src="{{ $githubProfile['avatar_url'] }}" class="rounded-circle mb-2" width="70" height="70" alt="Avatar">
            <h6 class="mb-0 text-white">{{ Auth::user()->name }}</h6>
            <small class="text-muted extra-small">{{ Auth::user()->email }}</small>
        </div>

        <a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
        <a href="{{ route('github.repos') }}"><i class="bi bi-journal-code me-2"></i> Repos & Gists Studio</a>
        <a href="{{ route('github.security') }}"><i class="bi bi-shield-lock me-2"></i> Security & OAuth Token</a>
        <a href="{{ route('github.timeline') }}" class="active"><i class="bi bi-diagram-3 me-2"></i> Orgs & Activity Feed</a>
        <a href="{{ route('github.profile') }}"><i class="bi bi-graph-up me-2"></i> GitHub Analytics</a>
        <a href="{{ route('users.index') }}"><i class="bi bi-people me-2"></i> Users</a>
        <a href="{{ route('login.history') }}"><i class="bi bi-clock-history me-2"></i> Login History</a>

        <hr class="text-secondary my-3">
        <form action="{{ route('logout') }}" method="POST" class="px-3">
            @csrf
            <button class="btn btn-danger w-100"><i class="bi bi-box-arrow-right me-1"></i> Logout</button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1"><i class="fa-solid fa-diagram-project text-warning me-2"></i>GitHub Organizations & Commits Activity Timeline</h2>
                <p class="text-muted mb-0">Inspect team memberships, track recent repository commits, and export profile data</p>
            </div>
            <div class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-download me-1"></i> Export Data
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="{{ route('github.export', ['format' => 'csv']) }}"><i class="fa-solid fa-file-csv text-success me-2"></i> CSV Format</a></li>
                    <li><a class="dropdown-item" href="{{ route('github.export', ['format' => 'xlsx']) }}"><i class="fa-solid fa-file-excel text-primary me-2"></i> XLSX Spreadsheet</a></li>
                    <li><a class="dropdown-item" href="{{ route('github.export', ['format' => 'json']) }}"><i class="fa-solid fa-file-code text-warning me-2"></i> JSON Payload</a></li>
                </ul>
            </div>
        </div>

        <!-- Organizations & Commit Activity Row -->
        <div class="row g-4 mb-4">
            <!-- Organizations Inspector -->
            <div class="col-lg-5">
                <div class="card p-4 h-100">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-sitemap text-primary me-2"></i>GitHub Organizations (Orgs)</h5>
                    <div style="max-height: 480px; overflow-y: auto;">
                        @forelse($orgs as $org)
                            <div class="p-3 border rounded-3 mb-3 bg-white shadow-sm">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="{{ $org['avatar_url'] ?? '' }}" class="rounded-3 me-3" width="45" height="45" alt="Org Avatar">
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">{{ $org['login'] ?? 'Organization' }}</h6>
                                        <span class="badge bg-info text-dark extra-small">{{ $org['role'] ?? 'Member' }}</span>
                                    </div>
                                </div>
                                <p class="text-muted extra-small mb-1">{{ $org['description'] ?? 'No organization bio set.' }}</p>
                                <div class="text-end">
                                    <span class="badge bg-light text-muted border"><i class="fa-solid fa-users me-1"></i>{{ $org['members_count'] ?? 1 }} Members</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="fa-solid fa-users-slash fa-3x mb-3 opacity-50"></i>
                                <h6>No Organizations Found</h6>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Commits Activity Feed Timeline -->
            <div class="col-lg-7">
                <div class="card p-4 h-100">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-timeline text-success me-2"></i>Recent Commits Activity Feed</h5>
                    <div style="max-height: 480px; overflow-y: auto;">
                        <ul class="list-group list-group-flush">
                            @forelse($commits as $c)
                                <li class="list-group-item px-0 py-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-dark small"><i class="fa-solid fa-code-commit text-success me-1"></i>{{ $c['message'] }}</strong>
                                        <code class="badge bg-light text-dark border">{{ $c['sha'] }}</code>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center extra-small text-muted">
                                        <span>
                                            <i class="fa-solid fa-book-bookmark text-primary me-1"></i><code>{{ $c['repo'] }}</code> 
                                            (<i class="fa-solid fa-code-branch text-muted me-1"></i>{{ $c['branch'] }})
                                        </span>
                                        <span><i class="fa-solid fa-clock me-1"></i>{{ $c['time'] }}</span>
                                    </div>
                                </li>
                            @empty
                                <div class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-code-pull-request fa-3x mb-3 opacity-50"></i>
                                    <h6>No Recent Commits Activity</h6>
                                </div>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
