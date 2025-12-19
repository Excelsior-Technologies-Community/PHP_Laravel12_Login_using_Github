# PHP_Laravel12_Socialite_GitHub_Login

A complete Laravel 12 application demonstrating **GitHub OAuth authentication** using **Laravel Socialite**.
This project explains the full flow from GitHub OAuth app creation to user login, session handling, and logout using a clean and beginner‑friendly approach.

---

## Project Overview

This repository helps developers understand:

* How GitHub OAuth works with Laravel Socialite
* How to configure OAuth credentials securely
* How to authenticate users using GitHub
* How to automatically create users on first login
* How to manage sessions and logout

This project is suitable for:

* Laravel beginners
* OAuth and Social Login learning
* Interview preparation
* Reference for Socialite integration

---

## Features

* Laravel 12
* GitHub OAuth Login using Laravel Socialite
* OAuth 2.0 secure authentication
* Automatic user creation on first login
* Stores GitHub ID, name, email, and avatar
* Session‑based authentication
* Logout functionality
* Clean Bootstrap UI
* Easily extendable to Google, Facebook, LinkedIn, etc.

---

## Project Screenshots

Home Page

<img src="https://github.com/user-attachments/assets/ca6f6f70-fca9-45b8-a927-88f517993f22" />

Login Page

<img src="https://github.com/user-attachments/assets/07cd1dab-0e5e-43e7-865a-bf415aebc688" />

Register Page

<img src="https://github.com/user-attachments/assets/668e23a3-d879-4379-a309-efec05457f8b" />

Dashboard Page

<img src="https://github.com/user-attachments/assets/ed1c4156-7313-4077-ad5f-2dc7dd7c26a3" />

GitHub Authorization Screen

<img src="https://github.com/user-attachments/assets/72bc5baf-c9ab-4abb-ac16-cf1290b9bace" />

GitHub App Creation Screen

<img src="https://github.com/user-attachments/assets/8d27d085-ce33-4861-9407-f477fd89d9f4" />

---

## Prerequisites

* PHP 8.1 or higher
* Laravel 12
* Composer
* GitHub Developer Account
* MySQL (optional)

---

## Installation Guide

### Step 1: Create Laravel Project

```bash
composer create-project laravel/laravel laravel-socialite
cd laravel-socialite
```

### Step 2: Install Laravel Socialite

```bash
composer require laravel/socialite
```

### Step 3: Create GitHub OAuth Application

Visit GitHub Developer Settings:

```
https://github.com/settings/apps
```

Create a new OAuth App with the following values:

Application Name:
Laravel Socialite App

Homepage URL:
[http://localhost:8000](http://localhost:8000)

Authorization Callback URL:
[http://localhost:8000/auth/github/callback](http://localhost:8000/auth/github/callback)

---

### Step 4: Add GitHub Credentials to `.env`

```env
GITHUB_CLIENT_ID=your_client_id
GITHUB_CLIENT_SECRET=your_client_secret
GITHUB_REDIRECT_URL=http://localhost:8000/auth/github/callback
```

---

### Step 5: Configure Socialite Services

Edit `config/services.php`:

```php
'github' => [
    'client_id' => env('GITHUB_CLIENT_ID'),
    'client_secret' => env('GITHUB_CLIENT_SECRET'),
    'redirect' => env('GITHUB_REDIRECT_URL'),
],
```

---

### Step 6: Create GitHub Controller

```bash
php artisan make:controller GitHubController
```

`app/Http/Controllers/GitHubController.php`

```php
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
                'avatar' => $githubUser->getAvatar(),
            ]
        );

        Auth::login($user);
        return redirect('/dashboard');
    }
}
```

---

### Step 7: Add Routes

Edit `routes/web.php`:

```php
use App\Http\Controllers\GitHubController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('login');
});

Route::get('/auth/github', [GitHubController::class, 'redirect']);
Route::get('/auth/github/callback', [GitHubController::class, 'callback']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
});
```

---

### Step 8: Update Users Table

Create migration:

```bash
php artisan make:migration add_social_columns_to_users_table
```

Migration file:

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('provider_id')->nullable();
    $table->string('avatar')->nullable();
});
```

Run migration:

```bash
php artisan migrate
```

---

### Step 9: Create Login Page

`resources/views/login.blade.php`

```html
<a href="{{ url('/auth/github') }}" class="btn btn-dark">
    Login with GitHub
</a>
```

---

### Step 10: Create Dashboard Page

`resources/views/dashboard.blade.php`

```html
<h2>Welcome, {{ auth()->user()->name }}</h2>
<img src="{{ auth()->user()->avatar }}" width="80">
<br><br>
<a href="/logout" class="btn btn-danger">Logout</a>
```

---

## Project Structure

```
laravel-socialite/
├── app/
│   └── Http/Controllers/GitHubController.php
├── resources/views/
│   ├── login.blade.php
│   └── dashboard.blade.php
├── routes/web.php
├── config/services.php
└── .env
```

---

