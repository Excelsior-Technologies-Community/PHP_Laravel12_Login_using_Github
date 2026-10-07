<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginHistoryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\GithubProfileController;
use App\Http\Controllers\GithubRepoGistController;
use App\Http\Controllers\GithubAccountSecurityController;
use App\Http\Controllers\GithubOrgsCommitsController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| GitHub OAuth
|--------------------------------------------------------------------------
*/

Route::get('/auth/github', [SocialiteController::class, 'githubRedirect'])
    ->name('auth.github');

Route::get('/auth/github/callback', [SocialiteController::class, 'githubCallback']);

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.custom');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
        ->name('register');

    Route::post('/register', [RegisterController::class, 'register']);

});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    Route::get('/login-history', [LoginHistoryController::class, 'index'])
        ->name('login.history');

    Route::get('/github/profile', [GithubProfileController::class, 'index'])
        ->name('github.profile');

    /* Module 1: Repositories & Gists */
    Route::get('/github/repos', [GithubRepoGistController::class, 'index'])
        ->name('github.repos');
    Route::post('/github/gists/create', [GithubRepoGistController::class, 'createGist'])
        ->name('github.gists.create');
    Route::post('/github/repos/star/{repoName}', [GithubRepoGistController::class, 'starRepo'])
        ->name('github.repos.star');

    /* Module 2: Account Security & OAuth Watchdog */
    Route::get('/github/security', [GithubAccountSecurityController::class, 'index'])
        ->name('github.security');
    Route::post('/github/security/unlink', [GithubAccountSecurityController::class, 'unlinkGithub'])
        ->name('github.security.unlink');

    /* Module 3: Orgs & Commits Timeline & Exporter */
    Route::get('/github/timeline', [GithubOrgsCommitsController::class, 'index'])
        ->name('github.timeline');
    Route::get('/github/export', [GithubOrgsCommitsController::class, 'exportData'])
        ->name('github.export');

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

});