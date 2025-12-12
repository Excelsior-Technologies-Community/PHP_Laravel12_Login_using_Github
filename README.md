# PHP_Laravel12_Socialite_GitHub_Login

A complete Laravel 12 application demonstrating **GitHub OAuth Login** using **Laravel Socialite**.  
This project includes full installation steps, controller setup, routing, views,  
GitHub OAuth app configuration, user creation, session login, and logout functionality.

---

##  Features
- GitHub OAuth Login using Laravel Socialite  
- Secure OAuth 2.0 authentication  
- Automatic user creation on first login  
- Stores GitHub ID, name, email, and avatar  
- Session-based login system  
- Logout support  
- Clean Bootstrap UI  
- Extendable for Google, Facebook, LinkedIn, and more  

---

## Screenshots

### Login Page
![Login Screenshot](https://github.com/user-attachments/assets/ca6f6f70-fca9-45b8-a927-88f517993f22)

### GitHub Authorization Screen
![GitHub Auth](https://github.com/user-attachments/assets/07cd1dab-0e5e-43e7-865a-bf415aebc688)

### Dashboard Page
![Dashboard](https://github.com/user-attachments/assets/668e23a3-d879-4379-a309-efec05457f8b)

---

## Prerequisites
- PHP 8.1+
- Laravel 12
- Composer
- GitHub Developer Account
- MySQL (optional)

---

# Installation Guide

## Step 1 — Create Laravel Project
```bash
composer create-project laravel/laravel laravel-socialite
cd laravel-socialite

Step 2 — Install Socialite
composer require laravel/socialite

Step 3 — Create GitHub OAuth App

Visit:
https://github.com/settings/apps
 → New OAuth App

Use:

Setting	Value
Application Name	Laravel Socialite App
Homepage URL	http://localhost:8000

Authorization Callback URL	http://localhost:8000/auth/github/callback
Step 4 — Add GitHub Credentials to .env
GITHUB_CLIENT_ID=your_client_id
GITHUB_CLIENT_SECRET=your_client_secret
GITHUB_REDIRECT_URL=http://localhost:8000/auth/github/callback

Step 5 — Configure Services

Edit: config/services.php

'github' => [
    'client_id'     => env('GITHUB_CLIENT_ID'),
    'client_secret' => env('GITHUB_CLIENT_SECRET'),
    'redirect'      => env('GITHUB_REDIRECT_URL'),
],

Step 6 — Create Controller
php artisan make:controller GitHubController

GitHubController.php
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

Step 7 — Add Routes

Add the following to routes/web.php:

use App\Http\Controllers\GitHubController;

Route::get('/auth/github', [GitHubController::class, 'redirect']);
Route::get('/auth/github/callback', [GitHubController::class, 'callback']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
});

Step 8 — Update Users Table
php artisan make:migration add_social_columns_to_users_table


Add inside migration:

Schema::table('users', function (Blueprint $table) {
    $table->string('provider_id')->nullable();
    $table->string('avatar')->nullable();
});


Run migration:

php artisan migrate

Step 9 — Create Login Page

Create: resources/views/login.blade.php

<a href="{{ url('/auth/github') }}" class="btn btn-dark">
    Login with GitHub
</a>

Step 10 — Dashboard Page

Create: resources/views/dashboard.blade.php

<h2>Welcome, {{ auth()->user()->name }}</h2>
<img src="{{ auth()->user()->avatar }}" width="80">
<br><br>
<a href="/logout" class="btn btn-danger">Logout</a>

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

