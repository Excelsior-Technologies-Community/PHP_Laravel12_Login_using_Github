<?php

namespace App\Http\Controllers;

use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard',[

            'totalUsers'=>User::count(),

            'githubUsers'=>User::whereNotNull('github_id')->count(),

            'normalUsers'=>User::whereNull('github_id')->count(),

            'todayUsers'=>User::whereDate(
                'created_at',
                today()
            )->count(),

        ]);
    }
}