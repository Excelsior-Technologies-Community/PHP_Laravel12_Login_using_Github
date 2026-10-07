<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GitHub Security & OAuth Token Watchdog | Laravel OAuth</title>
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
        <a href="{{ route('github.security') }}" class="active"><i class="bi bi-shield-lock me-2"></i> Security & OAuth Token</a>
        <a href="{{ route('github.timeline') }}"><i class="bi bi-diagram-3 me-2"></i> Orgs & Activity Feed</a>
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
                <h2 class="fw-bold mb-1"><i class="fa-solid fa-shield-halved text-success me-2"></i>GitHub Account Linker & OAuth Security Watchdog</h2>
                <p class="text-muted mb-0">Manage GitHub connection status, monitor token scopes, and inspect active session audit logs</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4">
                <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Account Linker & OAuth Token Status Row -->
        <div class="row g-4 mb-4">
            <!-- Account Linker Studio -->
            <div class="col-lg-6">
                <div class="card p-4 h-100">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-link text-primary me-2"></i>GitHub Account Connection Studio</h5>
                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Current Connection Status:</span>
                            @if($tokenStatus['is_connected'])
                                <span class="badge bg-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i>GitHub Connected</span>
                            @else
                                <span class="badge bg-secondary fw-bold"><i class="fa-solid fa-link-slash me-1"></i>Disconnected</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center small">
                            <span class="text-muted">GitHub User ID:</span>
                            <code class="text-dark fw-bold">{{ $tokenStatus['github_id'] }}</code>
                        </div>
                    </div>

                    <div class="mt-auto">
                        @if($tokenStatus['is_connected'])
                            <form action="{{ route('github.security.unlink') }}" method="POST" onsubmit="return confirm('Unlink your GitHub account from this profile?');">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100 fw-bold">
                                    <i class="fa-solid fa-link-slash me-1"></i> Unlink GitHub Account
                                </button>
                            </form>
                        @else
                            <a href="{{ route('auth.github') }}" class="btn btn-dark w-100 fw-bold">
                                <i class="fa-brands fa-github me-1"></i> Connect GitHub Account
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- OAuth Token Watchdog -->
            <div class="col-lg-6">
                <div class="card p-4 h-100 bg-dark text-white">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-key text-warning me-2"></i>OAuth Token Scope Watchdog</h5>
                    <div class="bg-black bg-opacity-50 p-3 rounded-3 border border-secondary mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted extra-small">Token Hash:</span>
                            <code class="text-warning extra-small font-monospace">{{ $tokenStatus['token_masked'] }}</code>
                        </div>
                        <div class="d-flex justify-content-between align-items-center extra-small text-muted mb-2">
                            <span>Access Token Status:</span>
                            <strong class="{{ $tokenStatus['has_access_token'] ? 'text-success' : 'text-danger' }}">
                                {{ $tokenStatus['has_access_token'] ? 'Active & Valid' : 'Missing' }}
                            </strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center extra-small text-muted">
                            <span>Refresh Token Status:</span>
                            <strong class="text-info">{{ $tokenStatus['has_refresh_token'] ? 'Available' : 'Standard OAuth Scope' }}</strong>
                        </div>
                    </div>

                    <div class="small">
                        <span class="text-muted d-block mb-1">Granted Token Scopes:</span>
                        <div class="d-flex gap-1 flex-wrap">
                            @foreach($tokenStatus['scopes'] as $scope)
                                <span class="badge bg-secondary border border-dark">{{ $scope }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security & Login Location Audit Trail -->
        <div class="card p-4">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-user-shield text-dark me-2"></i>Login Security & Device Location Inspector</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Login Method</th>
                            <th>IP Address</th>
                            <th>Browser / Device Agent</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loginHistories as $history)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($history->login_method == 'GitHub')
                                        <span class="badge bg-dark"><i class="bi bi-github me-1"></i>GitHub OAuth</span>
                                    @else
                                        <span class="badge bg-primary"><i class="bi bi-envelope me-1"></i>Email Login</span>
                                    @endif
                                </td>
                                <td><code class="text-primary">{{ $history->ip_address }}</code></td>
                                <td><small class="text-muted">{{ Str::limit($history->browser, 50) }}</small></td>
                                <td><small class="text-muted">{{ \Carbon\Carbon::parse($history->login_at)->format('d M Y, h:i A') }}</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No security login history records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
