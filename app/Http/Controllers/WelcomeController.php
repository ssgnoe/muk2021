<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function welcomePage() {
        $user = new User(['name' => 'Max Muster', 'email' => 'max2@muster.de', 'password' => 'Bla']);
        $user->name = 'Miriam Musterfrau';
        $user->save();
        return view('welcome');
    }

    public function user() {
        $users = User::where('email', '!=', 'max2@muster.de')->get();
        dd($users);
    }
}
