<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;

use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{

  public function store() {

    $user = new Users();

    $user->name = request('username');
    $user->score = request('score');
    $user->difficulty = request('difficulty');
    
    $user->save();

    return redirect('/leaderboard');
  }

  public function index() {

    $users = DB::table('users')
                ->orderBy('score', 'desc')
                ->get();      

    return view('leaderboard', [
      'users' => $users,
    ]);
  }
}