<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GitHub Analytics</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


</head>


<body class="bg-light">
    <div class="container py-4">

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">
                    <i class="bi bi-github"></i> GitHub Analytics
                </h2>
                <p class="text-muted mb-0">
                    Connected GitHub account insights
                </p>
            </div>

            <a href="{{ route('dashboard') }}" class="btn btn-dark">
                Back Dashboard
            </a>
        </div>


        <!-- Profile Card -->
        <div class="card shadow border-0 rounded-4 mb-4">
            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-md-3 text-center">

                        <img
                            src="{{ $profile['avatar_url'] }}"
                            class="rounded-circle shadow"
                            width="150"
                            height="150">

                    </div>


                    <div class="col-md-9">

                        <h2 class="fw-bold">
                            {{ $profile['name'] ?: $profile['login'] }}
                        </h2>

                        <h5 class="text-muted">
                            {{ '@' . $profile['login'] }}
                        </h5>


                        <p>
                            {{ $profile['bio'] ?: 'No bio available' }}
                        </p>


                        <div class="row">

                            <div class="col-md-4">
                                <strong>Company</strong>
                                <p>
                                    {{ $profile['company'] ?: 'N/A' }}
                                </p>
                            </div>


                            <div class="col-md-4">
                                <strong>Location</strong>
                                <p>
                                    {{ $profile['location'] ?: 'N/A' }}
                                </p>
                            </div>


                            <div class="col-md-4">
                                <strong>Website</strong>
                                <p>
                                    @if($profile['blog'])
                                    <a href="{{ $profile['blog'] }}" target="_blank">
                                        Visit
                                    </a>
                                    @else
                                    N/A
                                    @endif
                                </p>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>



        <!-- Statistics -->

        <div class="row mb-4">


            <div class="col-md-3 mb-3">

                <div class="card shadow border-0 bg-primary text-white">

                    <div class="card-body">

                        <h6>
                            Followers
                        </h6>

                        <h2>
                            {{ $profile['followers'] }}
                        </h2>

                    </div>

                </div>

            </div>



            <div class="col-md-3 mb-3">

                <div class="card shadow border-0 bg-success text-white">

                    <div class="card-body">

                        <h6>
                            Following
                        </h6>

                        <h2>
                            {{ $profile['following'] }}
                        </h2>

                    </div>

                </div>

            </div>



            <div class="col-md-3 mb-3">

                <div class="card shadow border-0 bg-dark text-white">

                    <div class="card-body">

                        <h6>
                            Stars
                        </h6>

                        <h2>
                            {{ $totalStars }}
                        </h2>

                    </div>

                </div>

            </div>



            <div class="col-md-3 mb-3">

                <div class="card shadow border-0 bg-warning">

                    <div class="card-body">

                        <h6>
                            Top Language
                        </h6>

                        <h2>
                            {{ $topLanguage }}
                        </h2>

                    </div>

                </div>

            </div>


        </div>



        <!-- Search -->

        <div class="card shadow border-0 mb-4">

            <div class="card-body">

                <form method="GET">

                    <div class="input-group">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search repository..."
                            value="{{ $search }}">


                        <button class="btn btn-primary">

                            Search

                        </button>

                    </div>

                </form>

            </div>

        </div>



        <!-- Repositories -->

        <h3 class="fw-bold mb-3">
            Repositories
        </h3>


        <div class="row">


            @forelse($repositories as $repo)

            <div class="col-md-6 mb-4">


                <div class="card shadow border-0 h-100">


                    <div class="card-body">


                        <h4 class="fw-bold">

                            {{ $repo['name'] }}

                        </h4>


                        <p class="text-muted">

                            {{ $repo['description'] ?? 'No description available' }}

                        </p>


                        <div class="mb-3">


                            @if($repo['language'])

                            <span class="badge bg-primary">

                                {{ $repo['language'] }}

                            </span>

                            @endif


                            <span class="badge bg-warning text-dark">

                                ⭐ {{ $repo['stargazers_count'] }}

                            </span>


                            <span class="badge bg-success">

                                🍴 {{ $repo['forks_count'] }}

                            </span>


                        </div>


                        <p>

                            Updated:

                            {{ \Carbon\Carbon::parse($repo['updated_at'])->format('d M Y') }}

                        </p>


                        <a
                            href="{{ $repo['html_url'] }}"
                            target="_blank"
                            class="btn btn-dark">

                            View Repository

                        </a>


                    </div>


                </div>


            </div>


            @empty


            <div class="text-center">

                <h4>
                    No repositories found
                </h4>

            </div>


            @endforelse


        </div>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>