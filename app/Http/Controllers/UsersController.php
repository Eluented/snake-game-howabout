<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;

class UsersController extends Controller
{

  public function index() {

    $users = Users::latest()->orderBy('score', 'desc')->get();      

    return view('leaderboard', [
      'users' => $users,
    ]);
  }
}