<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function githubRedirect()
    {
        return Socialite::driver('github')->redirect();
    }

    public function githubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();
            
            // Handle email (GitHub might not return email if not public)
            $email = $githubUser->email;
            if (!$email) {
                $email = 'github-' . $githubUser->id . '@example.com';
            }
            
            // Check if user exists by github_id
            $user = User::where('github_id', $githubUser->id)->first();
            
            if (!$user) {
                // Check if user exists by email
                $user = User::where('email', $email)->first();
                
                if ($user) {
                    // Update existing user with GitHub credentials
                    $user->update([
                        'github_id' => $githubUser->id,
                        'github_token' => $githubUser->token,
                        'github_refresh_token' => $githubUser->refreshToken,
                    ]);
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $githubUser->name ?? $githubUser->nickname,
                        'email' => $email,
                        'password' => bcrypt(Str::random(16)),
                        'github_id' => $githubUser->id,
                        'github_token' => $githubUser->token,
                        'github_refresh_token' => $githubUser->refreshToken,
                    ]);
                }
            } else {
                // Update tokens for existing GitHub user
                $user->update([
                    'github_token' => $githubUser->token,
                    'github_refresh_token' => $githubUser->refreshToken,
                ]);
            }
            
            // Log the user in
            Auth::login($user, true);
            
            return redirect()->route('dashboard');
            
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'github' => 'GitHub authentication failed. Please try again.'
            ]);
        }
    }
}