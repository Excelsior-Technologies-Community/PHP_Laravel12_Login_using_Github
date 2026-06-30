<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->oldest()->paginate(4);

        return view('users.index', [
            'users' => $users,
            'totalUsers' => User::count(),
            'githubUsers' => User::whereNotNull('github_id')->count(),
            'emailUsers' => User::whereNull('github_id')->count(),
        ]);
    }
}