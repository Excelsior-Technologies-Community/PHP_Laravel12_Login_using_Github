<?php

namespace App\Http\Controllers;

use App\Models\LoginHistory;
use Illuminate\Http\Request;

class LoginHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = LoginHistory::with('user');

        if ($request->search) {

            $query->where('login_method', 'like', '%' . $request->search . '%')

                  ->orWhereHas('user', function ($q) use ($request) {

                      $q->where('name', 'like', '%' . $request->search . '%')

                        ->orWhere('email', 'like', '%' . $request->search . '%');

                  });

        }

        $histories = $query->oldest()->paginate(4);

        return view('login-history.index', [

            'histories' => $histories,

            'totalLogins' => LoginHistory::count(),

            'githubLogins' => LoginHistory::where('login_method', 'GitHub')->count(),

            'emailLogins' => LoginHistory::where('login_method', 'Email')->count(),

        ]);
    }
}