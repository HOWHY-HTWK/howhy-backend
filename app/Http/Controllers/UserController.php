<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function check()
    {
        return [
            'success' => true
        ];
    }

    public function index()
    {
        return Auth::user();
    }

    public function changeUsername(){
        request()->validate([
            'username' => 'required|min:5|max:20',
        ]);

        $user = Auth::user();
        $user->name = request('username');
        $user->save();

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
}
