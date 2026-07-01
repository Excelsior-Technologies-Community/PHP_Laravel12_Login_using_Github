<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class GithubProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->github_id || !$user->github_token) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'GitHub account is not connected.');
        }

        /*
        |--------------------------------------------------------------------------
        | Fetch GitHub Profile
        |--------------------------------------------------------------------------
        */

        $profileResponse = Http::withToken($user->github_token)
            ->acceptJson()
            ->get('https://api.github.com/user');

        if (!$profileResponse->successful()) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Unable to fetch GitHub profile.');
        }

        $profile = $profileResponse->json();
        

        /*
        |--------------------------------------------------------------------------
        | Fetch User Repositories
        |--------------------------------------------------------------------------
        */

        $repoResponse = Http::withToken($user->github_token)
            ->acceptJson()
            ->get('https://api.github.com/user/repos', [
                'sort' => 'updated',
                'direction' => 'desc',
                'per_page' => 100,
            ]);

        $repositories = collect($repoResponse->json());

        /*
        |--------------------------------------------------------------------------
        | Search Repository
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $keyword = strtolower($request->search);

            $repositories = $repositories->filter(function ($repo) use ($keyword) {

                return str_contains(
                    strtolower($repo['name']),
                    $keyword
                );

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Statistics
        |--------------------------------------------------------------------------
        */

        $totalStars = $repositories->sum('stargazers_count');

        $totalForks = $repositories->sum('forks_count');

        $languages = [];

        foreach ($repositories as $repo) {

            if (!empty($repo['language'])) {

                $language = $repo['language'];

                if (!isset($languages[$language])) {
                    $languages[$language] = 0;
                }

                $languages[$language]++;

            }

        }

        arsort($languages);

        $topLanguage = count($languages)
            ? array_key_first($languages)
            : 'N/A';

        $mostStarredRepo = $repositories->sortByDesc('stargazers_count')->first();

        return view('github.profile', [

            'profile' => $profile,

            'repositories' => $repositories,

            'search' => $request->search,

            'totalStars' => $totalStars,

            'totalForks' => $totalForks,

            'topLanguage' => $topLanguage,

            'mostStarredRepo' => $mostStarredRepo,

        ]);
    }
}