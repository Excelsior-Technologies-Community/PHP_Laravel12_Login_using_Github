<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GitHub Repos & Gists Explorer | Laravel OAuth Studio</title>
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
        .code-pre { background: #1e1e1e; color: #d4d4d4; padding: 15px; border-radius: 8px; font-family: 'Courier New', monospace; }
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
        <a href="{{ route('github.repos') }}" class="active"><i class="bi bi-journal-code me-2"></i> Repos & Gists Studio</a>
        <a href="{{ route('github.security') }}"><i class="bi bi-shield-lock me-2"></i> Security & OAuth Token</a>
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
                <h2 class="fw-bold mb-1"><i class="fa-solid fa-code-branch text-primary me-2"></i>GitHub Repositories & Gist Studio</h2>
                <p class="text-muted mb-0">Explore authorized repositories, star projects & publish code snippets directly to GitHub</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Repositories Filter Bar -->
        <div class="card p-3 mb-4">
            <form method="GET" action="{{ route('github.repos') }}" class="row g-2 align-items-center">
                <div class="col-md-7">
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search repositories by name or keyword...">
                </div>
                <div class="col-md-3">
                    <select name="language" class="form-select">
                        <option value="">All Programming Languages</option>
                        @foreach($availableLanguages as $lang)
                            <option value="{{ $lang }}" {{ strtolower($language) === strtolower($lang) ? 'selected' : '' }}>{{ $lang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fa-solid fa-filter me-1"></i> Filter Repos</button>
                </div>
            </form>
        </div>

        <!-- Repositories Grid & Gist Studio Row -->
        <div class="row g-4 mb-4">
            <!-- Repositories Grid (Left 7 Cols) -->
            <div class="col-lg-7">
                <div class="card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="fa-solid fa-cubes text-primary me-2"></i>Your Repositories ({{ count($repos) }})</h5>
                        <span class="badge bg-light text-dark border">OAuth Access Active</span>
                    </div>

                    <div class="row g-3" style="max-height: 520px; overflow-y: auto;">
                        @forelse($repos as $r)
                            <div class="col-12">
                                <div class="p-3 border rounded-3 bg-white hover-shadow">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="fw-bold mb-1">
                                                <a href="{{ $r['html_url'] ?? '#' }}" target="_blank" class="text-decoration-none text-primary">
                                                    <i class="fa-solid fa-book-bookmark me-1"></i>{{ $r['name'] }}
                                                </a>
                                                @if(!empty($r['private']))
                                                    <span class="badge bg-danger ms-1">Private</span>
                                                @else
                                                    <span class="badge bg-light text-dark border ms-1">Public</span>
                                                @endif
                                            </h6>
                                            <p class="text-muted extra-small mb-2">{{ $r['description'] ?? 'No description provided.' }}</p>
                                        </div>
                                        <form action="{{ route('github.repos.star', $r['name']) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-warning btn-sm fw-bold">
                                                <i class="fa-solid fa-star me-1"></i> Star
                                            </button>
                                        </form>
                                    </div>
                                    <div class="d-flex gap-3 align-items-center extra-small text-muted border-top pt-2">
                                        <span><i class="fa-solid fa-circle text-warning me-1"></i>{{ $r['language'] ?? 'Text' }}</span>
                                        <span><i class="fa-solid fa-star text-warning me-1"></i>{{ $r['stargazers_count'] ?? 0 }} Stars</span>
                                        <span><i class="fa-solid fa-code-fork text-success me-1"></i>{{ $r['forks_count'] ?? 0 }} Forks</span>
                                        <span><i class="fa-solid fa-circle-dot text-info me-1"></i>{{ $r['open_issues_count'] ?? 0 }} Issues</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="fa-solid fa-folder-open fa-3x mb-3 opacity-50"></i>
                                <h6>No Repositories Found</h6>
                                <p class="small">Try adjusting search keyword or language filter.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Gist Creator & Snippet Manager (Right 5 Cols) -->
            <div class="col-lg-5">
                <div class="card p-4 h-100">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-plus-circle text-success me-2"></i>Publish GitHub Gist</h5>
                    <form action="{{ route('github.gists.create') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label fw-bold extra-small text-dark">Gist Description</label>
                            <input type="text" name="description" class="form-control form-control-sm" required placeholder="e.g. Laravel OAuth Socialite Callback Handler">
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold extra-small text-dark">Filename & Extension</label>
                            <input type="text" name="filename" class="form-control form-control-sm" required placeholder="SocialiteController.php">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold extra-small text-dark">Code Content</label>
                            <textarea name="code" rows="5" class="form-control form-control-sm font-monospace" required placeholder="<?php // Type code snippet here... ?>"></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_public" value="1" id="isPublic" checked>
                                <label class="form-check-label extra-small fw-bold" for="isPublic">Public Gist</label>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm fw-bold px-3">
                                <i class="fa-solid fa-upload me-1"></i> Publish Gist
                            </button>
                        </div>
                    </form>

                    <h6 class="fw-bold text-dark border-top pt-3 mb-2"><i class="fa-solid fa-code me-2"></i>Your Published Gists ({{ count($gists) }})</h6>
                    <div style="max-height: 200px; overflow-y: auto;">
                        @foreach($gists as $g)
                            <div class="p-2 border rounded-3 mb-2 bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong class="small text-dark text-truncate" style="max-width: 200px;">{{ $g['description'] ?? 'Untitled Gist' }}</strong>
                                    <span class="badge bg-secondary extra-small">{{ !empty($g['public']) ? 'Public' : 'Secret' }}</span>
                                </div>
                                <small class="text-muted extra-small d-block">{{ \Carbon\Carbon::parse($g['created_at'])->diffForHumans() }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
