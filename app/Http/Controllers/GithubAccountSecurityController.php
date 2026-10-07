<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GithubAccountSecurityController extends Controller
{
    /**
     * Display GitHub Account Linker & OAuth Security Watchdog
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $loginHistories = $user->loginHistories()->latest()->take(10)->get();

        $tokenStatus = [
            'is_connected' => !empty($user->github_id),
            'github_id' => $user->github_id ?? 'Not Linked',
            'has_access_token' => !empty($user->github_token),
            'has_refresh_token' => !empty($user->github_refresh_token),
            'token_masked' => $user->github_token ? substr($user->github_token, 0, 8) . '****************' : 'N/A',
            'scopes' => ['read:user', 'user:email', 'repo', 'gist'],
            'status_badge' => !empty($user->github_id) ? 'Active & Verified' : 'Disconnected',
        ];

        return view('github.security', [
            'user' => $user,
            'tokenStatus' => $tokenStatus,
            'loginHistories' => $loginHistories,
            'githubProfile' => [
                'name' => $user->name,
                'email' => $user->email,
                'avatar_url' => "https://ui-avatars.com/api/?name=" . urlencode($user->name) . "&background=0D6EFD&color=fff",
            ]
        ]);
    }

    /**
     * Unlink GitHub OAuth Account
     */
    public function unlinkGithub(Request $request)
    {
        $user = Auth::user();

        // Ensure user has a password or email so they won't get locked out
        if (empty($user->password) && empty($user->email)) {
            return redirect()->back()->with('error', 'Cannot unlink GitHub account without an email or password configured.');
        }

        $user->update([
            'github_id' => null,
            'github_token' => null,
            'github_refresh_token' => null,
        ]);

        return redirect()->back()->with('success', 'GitHub account unlinked successfully. You can link it again anytime!');
    }
}
