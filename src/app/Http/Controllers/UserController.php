<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Goal;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function show($hashID)
    {
        $user = User::where('hashed_id', $hashID)->first();

        return view('users/show')->with([
            'user' => $user,
            'goals' => $user->goals,
        ]);
    }



    public function welcome()
    {
        return view('users/welcome');
    }

    public function index(User $user)
    {
        return view('users/index')->with([
            'own_posts' => $user->getOwnPaginateByLimit(),
            // 'published_posts' => $user->getPublishedPaginateByLimit(),

        ]);
    }
    public function admin(User $user)
    {
        return view('users/admin')->with([
            'own_posts' => $user->getOwnPaginateByLimit(),
        ]);
    }
    public function reset(User $user)
    {
        $user->resetGoalsSet();

        return redirect('/dashboard');
    }

    public function apiindex(User $user)
    {
        $memberList = $user->getSetPaginateByLimit();
        return $memberList;
    }
}
