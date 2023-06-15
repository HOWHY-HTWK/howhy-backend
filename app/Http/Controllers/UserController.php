<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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

    public function changeUsername()
    {
        request()->validate([
            'name' => [
                'required',
                'min:5',
                'max:20',
                Rule::unique(User::class),
            ],
        ]);

        $user = Auth::user();
        $user->name = request('name');
        $user->save();

        return Auth::user();
    }

    public function getAll()
    {
        return User::all();
    }

    public function ranking()
    {
        $users = User::orderBy('score', 'desc')
            ->take(5)
            ->get();

        return $users;
    }

    public function makeEditor($id)
    {
        $user = User::find($id);
        $user->role = 'creator';
        $user->save();

        return true;
    }

    public function deleteAccount()
    {
        $user = User::find(Auth::user()->id);
        $user->delete();

        return [
            'success' => true
        ];
    }
}
