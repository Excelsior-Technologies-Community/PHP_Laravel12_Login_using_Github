## PHP_Laravel12_Socialite_GitHub_Login
Introduction
A complete Laravel 12 application demonstrating GitHub Login using Laravel Socialite.
This project includes full installation steps, controller setup, routing, views,
GitHub OAuth configuration, and authentication flow.


# Features
• GitHub OAuth Login using Laravel Socialite
• Secure authentication flow
• Automatic user creation on first login
• Session-based login
• Logout functionality
• Clean Bootstrap UI
• Easy to extend with other social logins

# Images

# 1
<img width="1366" height="929" alt="image" src="https://github.com/user-attachments/assets/ca6f6f70-fca9-45b8-a927-88f517993f22" />
# 2
<img width="1016" height="919" alt="image" src="https://github.com/user-attachments/assets/07cd1dab-0e5e-43e7-865a-bf415aebc688" />
# 3
<img width="881" height="970" alt="image" src="https://github.com/user-attachments/assets/668e23a3-d879-4379-a309-efec05457f8b" />

# ADD TO GITHUB 

# 1 <img width="1920" height="932" alt="image" src="https://github.com/user-attachments/assets/91bfe6d3-bc2d-441b-a2c9-a5deecdaad18" />

# 2 <img width="1920" height="932" alt="image" src="https://github.com/user-attachments/assets/2841a9a8-a37f-4473-b9eb-30650fb7f9ed" />


# Prerequisites
• PHP 8.1+
• Laravel 12
• Composer
• GitHub Developer Account
• MySQL (optional)


Step 1 – Create Laravel Project
Run the following command:

composer create-project laravel/laravel laravel-socialite
cd laravel-socialite
Step 2 – Install Socialite
composer require laravel/socialite
Step 3 – Create GitHub OAuth App
Go to: https://github.com/settings/apps

Click: New OAuth App

Set:
Application Name: Laravel Socialite App
Homepage URL: http://localhost:8000
Authorization Callback URL: http://localhost:8000/auth/github/callback
Step 4 – Add GitHub Credentials to .env
Add below keys:

GITHUB_CLIENT_ID=your_client_id
GITHUB_CLIENT_SECRET=your_client_secret
GITHUB_REDIRECT_URL=http://localhost:8000/auth/github/callback
Step 5 – Configure Services
Edit config/services.php:

'github' => [
    'client_id'     => env('GITHUB_CLIENT_ID'),
    'client_secret' => env('GITHUB_CLIENT_SECRET'),
    'redirect'      => env('GITHUB_REDIRECT_URL'),
],
Step 6 – Create Controller
php artisan make:controller GitHubController
GitHubController Code
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GitHubController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('github')->redirect();
    }

    public function callback()
    {
        $githubUser = Socialite::driver('github')->user();

        $user = User::updateOrCreate(
            ['email' => $githubUser->getEmail()],
            [
                'name' => $githubUser->getName(),
                'provider_id' => $githubUser->getId(),
                'avatar' => $githubUser->getAvatar()
            ]
        );

        Auth::login($user);
        return redirect('/dashboard');
    }
}
Step 7 – Add Routes
Edit routes/web.php:

use App\Http\Controllers\GitHubController;

Route::get('/auth/github', [GitHubController::class, 'redirect']);
Route::get('/auth/github/callback', [GitHubController::class, 'callback']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');
Step 8 – Update Users Table Migration
Add columns:
php artisan make:migration add_social_columns_to_users_table

Schema::table('users', function (Blueprint $table) {
    $table->string('provider_id')->nullable();
    $table->string('avatar')->nullable();
});
Step 9 – Create Login View
resources/views/login.blade.php:

<a href="{{ url('/auth/github') }}" class="btn btn-dark">
    Login with GitHub
</a>
Step 10 – Dashboard View
resources/views/dashboard.blade.php:

<h2>Welcome, {{ auth()->user()->name }}</h2>
<img src="{{ auth()->user()->avatar }}" width="80">
Step 11 – Logout
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
});
Project Structure
laravel-socialite/
├── app/
│   └── Http/Controllers/GitHubController.php
├── resources/
│   └── views/
│       ├── login.blade.php
│       └── dashboard.blade.php
├── routes/web.php
├── config/services.php
└── .env
Conclusion
This simple Laravel 12 application demonstrates how to implement GitHub Login using Socialite.
You can easily extend it to support Google, Facebook, Twitter, LinkedIn, and more.
