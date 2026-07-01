<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $githubStats = [

            'repositories' => 0,

            'stars' => 0,

            'forks' => 0,

            'language' => 'N/A',

        ];

        $user = Auth::user();

        // Default empty GitHub profile
        $profile = [];

        /*
        |--------------------------------------------------------------------------
        | GitHub Analytics
        |--------------------------------------------------------------------------
        */

        if ($user->github_token) {

            // Fetch GitHub Profile
            $profileResponse = Http::withToken($user->github_token)
                ->acceptJson()
                ->get('https://api.github.com/user');

            if ($profileResponse->successful()) {
                $profile = $profileResponse->json();
            }

            // Fetch GitHub Repositories
            $response = Http::withToken($user->github_token)
                ->acceptJson()
                ->get(
                    'https://api.github.com/user/repos',
                    [
                        'sort' => 'updated',
                        'per_page' => 100,
                    ]
                );

            if ($response->successful()) {

                $repositories = collect($response->json());

                $languages = [];

                foreach ($repositories as $repo) {

                    if (!empty($repo['language'])) {

                        $languages[$repo['language']] =
                            ($languages[$repo['language']] ?? 0) + 1;
                    }
                }

                arsort($languages);

                $githubStats = [

                    'repositories' => $repositories->count(),

                    'stars' => $repositories->sum('stargazers_count'),

                    'forks' => $repositories->sum('forks_count'),

                    'language' => count($languages)
                        ? array_key_first($languages)
                        : 'N/A',

                ];
            }
        }

        return view('dashboard', [

            /*
            |--------------------------------------------------------------------------
            | Existing Statistics
            |--------------------------------------------------------------------------
            */

            'totalUsers' => User::count(),

            'githubUsers' => User::whereNotNull('github_id')->count(),

            'normalUsers' => User::whereNull('github_id')->count(),

            'todayUsers' => User::whereDate(
                'created_at',
                today()
            )->count(),

            /*
            |--------------------------------------------------------------------------
            | GitHub Analytics
            |--------------------------------------------------------------------------
            */

            'githubRepositories' => $githubStats['repositories'],

            'githubStars' => $githubStats['stars'],

            'githubForks' => $githubStats['forks'],

            'githubLanguage' => $githubStats['language'],

            /*
            |--------------------------------------------------------------------------
            | GitHub Profile
            |--------------------------------------------------------------------------
            */

            'githubProfile' => $profile,

        ]);
    }
}