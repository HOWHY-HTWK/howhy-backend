<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index()
    {
        return Auth::user();
    }

    public function getAll()
    {
        return User::all();
    }

    public function makeEditor($id){
        $user = User::find($id);
        $user->role = 'creator';
        $user->save();

        return true;
    }

    //TODO not done
    public function userLogin()
    {
        return [
            'username' => request('username'),
            'role' => 'user',
        ];
    }

    public function userSignUp()
    {
        return true;
    }
}
